<?php
namespace App\Utils;

require_once 'PriorityQueueInterface.php';

class PriorityQueue implements PriorityQueueInterface
{

    protected $queue;
    protected $num_elements;
    protected $hashmap;
    protected $algorithm;

    protected $filePath;


    public function __construct($algorithm = 'crc32b',$filePath=null)
    {
       $this->filePath = $filePath ?? storage_path('app/cola.json');
       $this->algorithm = $algorithm;

        if (!file_exists($this->filePath)) {
            // Crea archivo JSON vacío
            file_put_contents($this->filePath, json_encode([
                'queue' => [],
                'num_elements' => 0,
                'hashmap' => []
            ]));
        }

        $this->load();
    }

    private function save(){
        file_put_contents($this->filePath,json_encode([

            'queue'=>$this->queue,
            'num_elements'=>$this->num_elements,
            'hashmap'=>$this->hashmap

        ]));
    }

    
    private function load()
    {
        if (!file_exists($this->filePath)) {
            $this->queue = [];
            $this->num_elements = 0;
            $this->hashmap = [];
            return;
        }

        $content = file_get_contents($this->filePath);
        $data = json_decode($content, true);

        if (!is_array($data)) {
            // Archivo corrupto o vacío
            $this->queue = [];
            $this->num_elements = 0;
            $this->hashmap = [];
            return;
        }

        // Ya podemos acceder sin miedo
        $this->queue = $data['queue'] ?? [];
        $this->num_elements = $data['num_elements'] ?? 0;
        $this->hashmap = $data['hashmap'] ?? [];
    }


    public function isEmpty(): bool
    {
        return $this->num_elements == 0;
    }


    public function contains($element): bool
    {
        return isset($this->hashmap[hash($this->algorithm, serialize($element))]);
    }

    public function push($element, $priority): bool
    {
        if ($this->isEmpty()) {
            $this->num_elements                              = 1;
            $this->queue[1]['element']                       = $element;
            $this->queue[1]['priority']                      = $priority;
            $this->queue[1]['timestamp']                     = time();
            $this->hashmap[hash($this->algorithm, serialize($element))] = 1;
            $this->save();
            return true;
        }

        $this->num_elements++;

        $this->up_heap($element, $this->num_elements, $priority);

        $this->save();

        return true;
    }


    public function pop()
    //creo q marca error
    {   
        if ($this->isEmpty()) {
            throw new EmptyQueueException("Queue is empty");
        }

        $first_element = $this->queue[1];
        $last_element  = $this->queue[$this->num_elements];

        $this->num_elements--;
        array_pop($this->queue);

        unset($this->hashmap[hash($this->algorithm, serialize($first_element['element']))]);

        $this->down_heap($last_element['element'], 1, $last_element['priority']);

        $this->save();

        return $first_element['element'];
    }


    public function change_priority($element, $new_priority): bool
    {
        $pos = $this->hashmap[hash($this->algorithm, serialize($element))];

        if ($pos) {
            if (($pos == 1) && ($this->queue[$pos]['priority'] < $new_priority)) {
                $this->down_heap($element, $pos, $new_priority);
                return true;
            } else if (($pos == 1) && ($this->queue[$pos]['priority'] > $new_priority)) {
                return true;


            } else if (($pos == $this->num_elements) && ($this->queue[$pos]['priority'] > $new_priority)) {
                $this->up_heap($element, $pos, $new_priority);
                return true;
            } else if (($pos == $this->num_elements) && ($this->queue[$pos]['priority'] < $new_priority)) {
                return true;


            } else {
                $fathers_position = intdiv($pos, 2);

                if ($new_priority < $this->queue[$fathers_position]['priority']) {
                    $this->up_heap($element, $pos, $new_priority);
                } else {
                    $this->down_heap($element, $pos, $new_priority);
                }

                return true;
            }

        } else {
            return false;
        }
    }

    public function purge(): void
    {
        array_splice($this->queue, 0);
        $this->num_elements = 0;

        array_splice($this->hashmap, 0);

        $this->save();
    }

    public function count(): int
    {
        return $this->num_elements;
    }


    private function down_heap($element, $pos, $priority): void
    {
        $top      = $pos;
        $tops_son = $top * 2;

        if (($tops_son < $this->num_elements) && ($this->queue[$tops_son+1]['priority'] < $this->queue[$tops_son]['priority'])) {
            $tops_son++;
        }

        while (($tops_son < $this->num_elements) && ($this->queue[$tops_son]['priority'] < $priority)) {
            $this->queue[$top] = $this->queue[$tops_son];
            $this->hashmap[hash($this->algorithm, $this->queue[$top]['element'])] = $top;

            $top               = $tops_son;
            $tops_son          = $top * 2;

            if (($tops_son < $this->num_elements) && ($this->queue[$tops_son+1]['priority'] < $this->queue[$tops_son]['priority'])) {
                $tops_son++;
            }
        }

        $this->queue[$top]['element']   = $element;
        $this->queue[$top]['priority']  = $priority;
        $this->queue[$top]['timestamp'] = time();
        $this->hashmap[hash($this->algorithm, serialize($element))] = $top;

        $this->hashmap[hash($this->algorithm, serialize($this->queue[$pos]['element']))] = $pos;
    }


    private function up_heap($element, $pos, $priority)
    {
        $next_position    = $pos;        //Pointer to the next free position in the queue.
        $fathers_position = intdiv($next_position, 2);  //Obtain father's position of the new element.

        while (($fathers_position > 0) &&
               ($this->queue[$fathers_position]['priority'] > $priority)) {
            $this->queue[$next_position] = $this->queue[$fathers_position];
            $this->hashmap[hash($this->algorithm, $this->queue[$next_position]['element'])] = $next_position;

            $next_position               = $fathers_position;
            $fathers_position            = intdiv($next_position, 2);
        }

        $this->queue[$next_position]['element']   = $element;
        $this->queue[$next_position]['priority']  = $priority;
        $this->queue[$next_position]['timestamp'] = time();
        $this->hashmap[hash($this->algorithm, serialize($element))] = $next_position;

        $this->hashmap[hash($this->algorithm, serialize($this->queue[$pos]['element']))] = $pos;
    }

    private function getPosition($element)
    {
        $position = hash($this->algorithm, serialize($element));
        if (isset($this->hashmap[$position])) {
            return $this->hashmap[$position];
        } else {
            return false;
        }
    }


    public function print(): void
    {
        for ($i=1; $i<=$this->num_elements; $i++) {
            echo $i." - ".$this->queue[$i]['priority']." - ".$this->queue[$i]['element']."\n";
        }
    }
}
?>