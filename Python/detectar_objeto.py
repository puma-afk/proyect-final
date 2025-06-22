import argparse
import cv2
import os
import sys
from ultralytics import YOLO  # YOLOv8

def main():
    parser = argparse.ArgumentParser(description="Detección de un objeto con YOLOv8")
    parser.add_argument('--image', required=True, help="Ruta de la imagen a procesar")
    parser.add_argument('--label', required=True, help="Etiqueta del objeto a detectar (en inglés)")
    parser.add_argument('--output', required=True, help="Ruta donde guardar la imagen procesada")
    
    args = parser.parse_args()

    # Verifica que la imagen existe
    if not os.path.isfile(args.image):
        print(f"ERROR: La imagen no existe en la ruta {args.image}")
        sys.exit(1)

    # Cargar modelo YOLOv8
    try:
       MODEL_PATH = os.path.join(os.path.dirname(__file__), 'yolov8m.pt')
       model = YOLO(MODEL_PATH)  # Asegúrate que tienes el modelo en la misma carpeta o da el path correcto
    except Exception as e:
        print(f"ERROR: No se pudo cargar el modelo YOLOv8: {e}")
        sys.exit(1)

    # Realizar la predicción
    results = model(args.image)

    # Obtener resultados y filtrar por etiqueta
    detecciones = results[0].boxes
    names = model.names

    image = cv2.imread(args.image)
    count = 0

    for box in detecciones:
        cls_id = int(box.cls[0].item())
        name = names[cls_id]
        if name.lower() == args.label.lower():
            count += 1
            x1, y1, x2, y2 = map(int, box.xyxy[0].tolist())
            cv2.rectangle(image, (x1, y1), (x2, y2), (0, 255, 0), 2)
            cv2.putText(image, name, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (0, 255, 0), 2)

    # Guardar imagen procesada
    os.makedirs(os.path.dirname(args.output), exist_ok=True)
    cv2.imwrite(args.output, image)

    # Devuelve el conteo
    print(count)

if __name__ == "__main__":
    main()
