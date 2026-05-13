import uvicorn
from fastapi import FastAPI, File, UploadFile
from fastapi.middleware.cors import CORSMiddleware
import tensorflow as tf
from PIL import Image
import numpy as np
import io
import os
from tensorflow.keras.applications.resnet50 import preprocess_input as resnet_preprocess

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# --- LOAD MODELS ---
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Load model dengan nama file yang sesuai hasil training kamu
model_resnet = tf.keras.models.load_model(os.path.join(BASE_DIR, "resnet50_binary_final.keras"))
model_mobilenet = tf.keras.models.load_model(os.path.join(BASE_DIR, "mobilenet_v3_binary_final.keras"))

CLASS_NAMES = ["Diseased", "Healthy"]

def preprocess_image(image_bytes, model_type):
    # SINRONISASI SIZE:
    # ResNet = 224, MobileNet = 300
    target_size = (224, 224) if model_type == "resnet" else (300, 300)

    img = Image.open(io.BytesIO(image_bytes))
    img = img.convert('RGB')
    img = img.resize(target_size)

    img_array = np.array(img)
    img_array = np.expand_dims(img_array, axis=0)

    if model_type == "resnet":
        # ResNet: WAJIB pakai preprocess_input (Zero-centering BGR)
        img_array = resnet_preprocess(img_array)
    else:
        # MobileNetV3: Raw (0-255) karena kodingan training pakai include_preprocessing internal
        img_array = img_array.astype(np.float32)

    return img_array

@app.post("/predict/{model_name}")
async def predict(model_name: str, file: UploadFile = File(...)):
    try:
        if model_name == "resnet":
            current_model = model_resnet
        elif model_name == "mobilenet":
            current_model = model_mobilenet
        else:
            return {"status": "error", "message": "Model not found"}

        contents = await file.read()
        processed_image = preprocess_image(contents, model_name)

        prediction = current_model.predict(processed_image)[0][0]

        # Logika Binary: 0=Diseased, 1=Healthy
        is_healthy = prediction >= 0.5
        predicted_class = "Healthy" if is_healthy else "Diseased"

        # Hitung confidence
        conf_value = prediction if is_healthy else (1 - prediction)

        return {
            "status": "success",
            "model_used": model_name,
            "detection": predicted_class,
            "confidence": f"{conf_value * 100:.2f}%",
            "all_predictions": {
                "Diseased": float(1 - prediction),
                "Healthy": float(prediction)
            }
        }
    except Exception as e:
        return {"status": "error", "message": str(e)}

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=5000)
