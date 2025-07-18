import sys
import json
import joblib
import numpy as np

# Carrega modelo, scaler e features
model, scaler, features = joblib.load('modelo_nivel_rio.joblib')

data = json.loads(sys.argv[1])
X = np.array([[data[f] for f in features]])
X_scaled = scaler.transform(X)
pred = model.predict(X_scaled)
print(float(pred[0]))
