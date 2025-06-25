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

        ], JSON_PRETTY_PRINT));
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
            $this->queue = [];
            $this->num_elements = 0;
            $this->hashmap = [];
            return;
        }

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
        $this->num_elements++;
        $this->queue[$this->num_elements]=[
            'elemento' => $element,
            'prioridad' => $priority,
            'timestamp' => time()
        ];

        $this->hashmap[hash($this->algorithm, serialize($element))] = $this->num_elements;


        $this->save();

        return true;
    }


    public function pop()

    {   
        if ($this->isEmpty()) {
            throw new EmptyQueueException("Queue is empty");
        }

        $first_element = $this->queue[1];

        unset($this->hashmap[hash($this->algorithm, serialize($first_element['elemento']))]);

        unset($this->queue[1]);
        $this->num_elements--;

        $new_queue = [];
        $new_hashmap = [];
        $count=1;

        foreach ($this->queue as $item) {
            $new_queue[$count] = $item;
            $new_hashmap[hash($this->algorithm, serialize($item['elemento']))] = $count;
            $count++;
        }

        $this->queue=$new_queue;
        $this->hashmap = $new_hashmap;

        $this->save();

        return $first_element['elemento'];
    }


    public function change_priority($element, $new_priority): bool
    {
        return false;
    }

    public function purge(): void
    {
        $this->queue = [];
        $this->num_elements = 0;
        $this->hashmap = [];
        $this->save();
    }

    public function count(): int
    {
        return $this->num_elements;
    }

    public function print(): void
    {
        for ($i=1; $i<=$this->num_elements; $i++) {
            echo $i." - ".$this->queue[$i]['prioridad']." - ".$this->queue[$i]['elemento']."\n";
        }
    }
}
?>