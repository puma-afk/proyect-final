import sys
import os
import json
from ultralytics import YOLO
import cv2

if len(sys.argv) < 3:
    sys.exit(1)

imagen_path = sys.argv[1]
salida_path = sys.argv[2]  

modelo_path = os.path.join(os.path.dirname(__file__), 'yolov8m.pt')
model = YOLO(modelo_path)

resultados = model(imagen_path, verbose=False)

if not resultados or not resultados[0].boxes:
    print(json.dumps({"personas_detectadas": 0}))
    sys.exit(0)


personas_detectadas = sum(1 for c in resultados[0].boxes.cls if int(c) == 0)


img_con_detecciones = resultados[0].plot()
cv2.imwrite(salida_path, img_con_detecciones)


print(json.dumps({"personas_detectadas": personas_detectadas}))
