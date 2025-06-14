<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\VoiceCommand; // Opcional: si quieres guardar comandos en BD
use Carbon\Carbon;

class VoiceGestureController extends Controller
{
    /**
     * Mostrar la página principal de control por voz y gestos
     */
    public function index()
    {
        return view('voice-gesture-control');
    }

    /**
     * Procesar comandos de voz y gestos recibidos desde JavaScript
     */
    public function processCommand(Request $request): JsonResponse
    {
        // Validar datos recibidos
        $request->validate([
            'type' => 'required|string|max:50',
            'command' => 'required|string|max:255',
            'timestamp' => 'nullable|string'
        ]);

        $commandType = $request->input('type');
        $commandData = $request->input('command');
        $timestamp = $request->input('timestamp');
        $userId = Auth::id(); // Usuario actual (si está autenticado)

        // Log del comando recibido
        Log::info("Comando recibido", [
            'type' => $commandType,
            'command' => $commandData,
            'user_id' => $userId,
            'ip' => $request->ip(),
            'timestamp' => $timestamp
        ]);

        try {
            // Procesar el comando según su tipo
            $response = $this->handleCommand($commandType, $commandData, $request);

            // Opcionalmente guardar en base de datos
            $this->saveCommandToDatabase($commandType, $commandData, $userId);

            return response()->json([
                'status' => 'success',
                'message' => $response['message'] ?? null,
                'action' => $response['action'] ?? null,
                'data' => $response['data'] ?? null,
                'redirect' => $response['redirect'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error("Error procesando comando", [
                'error' => $e->getMessage(),
                'command_type' => $commandType,
                'command_data' => $commandData
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar el comando'
            ], 500);
        }
    }

    /**
     * Manejar diferentes tipos de comandos
     */
    private function handleCommand(string $type, string $data, Request $request): array
    {
        switch ($type) {
            // === COMANDOS DE VOZ ===
            case 'greeting':
                return [
                    'message' => '¡Hola! Bienvenido al sistema de control por voz y gestos. ¿En qué puedo ayudarte?',
                    'action' => 'greeting'
                ];

            case 'goodbye':
                return [
                    'message' => '¡Hasta luego! Gracias por usar el sistema. Que tengas un excelente día.',
                    'action' => 'goodbye'
                ];

            case 'help':
                $helpText = 'Puedes usar comandos como: hola, adiós, página principal, configuración, ' .
                           'búsqueda, perfil, guardar, o usar gestos con las manos como OK, pulgar arriba, paz y señalar.';
                return [
                    'message' => $helpText,
                    'action' => 'help',
                    'data' => $this->getAvailableCommands()
                ];

            case 'navigate_home':
                return [
                    'message' => 'Navegando a la página principal',
                    'action' => 'redirect',
                    'redirect' => route('home') // Ajusta según tu ruta
                ];

            case 'search_mode':
                return [
                    'message' => 'Modo búsqueda activado. ¿Qué deseas buscar?',
                    'action' => 'activate_search'
                ];

            case 'open_profile':
                if (Auth::check()) {
                    return [
                        'message' => 'Abriendo tu perfil de usuario',
                        'action' => 'redirect',
                        'redirect' => route('profile.show') // Ajusta según tu ruta
                    ];
                } else {
                    return [
                        'message' => 'Necesitas iniciar sesión para ver tu perfil',
                        'action' => 'redirect',
                        'redirect' => route('login')
                    ];
                }

            case 'open_settings':
                return [
                    'message' => 'Abriendo configuración del sistema',
                    'action' => 'redirect',
                    'redirect' => route('settings') // Ajusta según tu ruta
                ];

            case 'save_data':
                // Ejemplo: guardar datos específicos
                return [
                    'message' => 'Datos guardados correctamente en el sistema',
                    'action' => 'save_complete'
                ];

            case 'logout':
                if (Auth::check()) {
                    Auth::logout();
                    return [
                        'message' => 'Sesión cerrada correctamente. ¡Hasta luego!',
                        'action' => 'redirect',
                        'redirect' => route('home')
                    ];
                } else {
                    return [
                        'message' => 'No hay ninguna sesión activa',
                        'action' => 'info'
                    ];
                }

            // === COMANDOS DE GESTOS ===
            case 'thumbs_up':
                return [
                    'message' => '¡Excelente! Gesto de aprobación recibido',
                    'action' => 'approval',
                    'data' => ['sentiment' => 'positive']
                ];

            case 'ok_gesture':
                return [
                    'message' => 'Perfecto, todo está bien. Comando confirmado',
                    'action' => 'confirmation',
                    'data' => ['confirmed' => true]
                ];

            case 'peace_gesture':
                return [
                    'message' => 'Paz y tranquilidad. Gesto de paz recibido',
                    'action' => 'peace_mode',
                    'data' => ['mode' => 'peaceful']
                ];

            case 'pointing':
                return [
                    'message' => 'Gesto de señalar detectado. ¿Quieres seleccionar algo?',
                    'action' => 'pointing_mode',
                    'data' => ['selection_mode' => true]
                ];

            case 'open_hand':
                return [
                    'message' => 'Mano abierta detectada. Modo de interacción activado',
                    'action' => 'interaction_mode'
                ];

            case 'fist':
                return [
                    'message' => 'Puño detectado. Modo de acción enérgica',
                    'action' => 'power_mode'
                ];

            // === COMANDOS PERSONALIZADOS ===
            case 'custom':
                return $this->handleCustomCommand($data, $request);

            case 'navigation_command':
                return $this->handleNavigationCommand($data);

            case 'data_command':
                return $this->handleDataCommand($data, $request);

            default:
                return [
                    'message' => 'Comando no reconocido. Intenta con otro comando o di "ayuda" para ver opciones disponibles.',
                    'action' => 'unknown_command'
                ];
        }
    }

    /**
     * Manejar comandos personalizados más complejos
     */
    private function handleCustomCommand(string $command, Request $request): array
    {
        $command = strtolower($command);

        // Comandos de navegación
        if (str_contains($command, 'dashboard') || str_contains($command, 'panel')) {
            return [
                'message' => 'Abriendo el panel de control',
                'action' => 'redirect',
                'redirect' => route('dashboard')
            ];
        }

        if (str_contains($command, 'usuarios') || str_contains($command, 'users')) {
            return [
                'message' => 'Mostrando lista de usuarios',
                'action' => 'redirect',
                'redirect' => route('users.index')
            ];
        }

        // Comandos de acción
        if (str_contains($command, 'crear') || str_contains($command, 'nuevo')) {
            return [
                'message' => 'Modo creación activado. ¿Qué deseas crear?',
                'action' => 'creation_mode'
            ];
        }

        if (str_contains($command, 'eliminar') || str_contains($command, 'borrar')) {
            return [
                'message' => 'Atención: Modo eliminación. Confirma con otro comando.',
                'action' => 'deletion_warning'
            ];
        }

        // Comando de búsqueda específica
        if (str_contains($command, 'buscar')) {
            $searchTerm = str_replace(['buscar', 'búsqueda'], '', $command);
            $searchTerm = trim($searchTerm);
            
            if (!empty($searchTerm)) {
                return [
                    'message' => "Buscando: {$searchTerm}",
                    'action' => 'search',
                    'data' => ['query' => $searchTerm],
                    'redirect' => route('search', ['q' => $searchTerm])
                ];
            }
        }

        // Comando no específico
        return [
            'message' => 'Comando personalizado recibido, pero no pude interpretarlo completamente.',
            'action' => 'custom_unrecognized',
            'data' => ['original_command' => $command]
        ];
    }

    /**
     * Manejar comandos de navegación
     */
    private function handleNavigationCommand(string $data): array
    {
        // Implementar lógica de navegación específica
        return [
            'message' => 'Comando de navegación procesado',
            'action' => 'navigation'
        ];
    }

    /**
     * Manejar comandos de datos
     */
    private function handleDataCommand(string $data, Request $request): array
    {
        // Implementar lógica de manejo de datos
        return [
            'message' => 'Comando de datos procesado',
            'action' => 'data_processed'
        ];
    }

    /**
     * Obtener lista de comandos disponibles
     */
    private function getAvailableCommands(): array
    {
        return [
            'voice_commands' => [
                'hola', 'adiós', 'ayuda', 'página principal', 'perfil',
                'configuración', 'búsqueda', 'guardar', 'cerrar sesión'
            ],
            'gesture_commands' => [
                'pulgar arriba', 'OK', 'paz', 'señalar', 'mano abierta', 'puño'
            ]
        ];
    }

    /**
     * Guardar comando en base de datos (opcional)
     */
    private function saveCommandToDatabase(string $type, string $command, ?int $userId): void
    {
        try {
            // Solo si tienes la tabla y modelo VoiceCommand
            /*
            VoiceCommand::create([
                'user_id' => $userId,
                'command_type' => $type,
                'command_text' => $command,
                'processed_at' => Carbon::now(),
                'ip_address' => request()->ip()
            ]);
            */

            // Alternativa usando DB directamente
            DB::table('voice_gesture_logs')->insert([
                'user_id' => $userId,
                'command_type' => $type,
                'command_text' => $command,
                'ip_address' => request()->ip(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        } catch (\Exception $e) {
            // Si no existe la tabla, no hacer nada o log el error
            Log::warning('No se pudo guardar comando en BD: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de comandos (opcional)
     */
    public function getCommandStats(): JsonResponse
    {
        try {
            $stats = DB::table('voice_gesture_logs')
                ->select('command_type', DB::raw('COUNT(*) as count'))
                ->groupBy('command_type')
                ->orderBy('count', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudieron obtener las estadísticas'
            ]);
        }
    }

    /**
     * API para obtener configuración del sistema
     */
    public function getSystemConfig(): JsonResponse
    {
        return response()->json([
            'voice_recognition' => [
                'language' => 'es-ES',
                'continuous' => true,
                'interim_results' => true
            ],
            'gesture_detection' => [
                'max_hands' => 2,
                'model_complexity' => 1,
                'min_detection_confidence' => 0.5,
                'min_tracking_confidence' => 0.5
            ],
            'available_commands' => $this->getAvailableCommands()
        ]);
    }
}