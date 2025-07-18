import sys
import json
import joblib
import numpy as np

# Carrega modelo, scaler e features
model, scaler, features = joblib.load('modelo_nivel_rio.joblib')

data = json.loads(sys.argv[1])
print('Recebido do PHP:', data, file=sys.stderr)

# Garante ordem correta das features
X = np.array([[data.get(f, 0) for f in features]])
print('Vetor de entrada para o modelo:', X, file=sys.stderr)
X_scaled = scaler.transform(X)
print('Vetor normalizado:', X_scaled, file=sys.stderr)
pred = model.predict(X_scaled)
print('Previsão do modelo:', pred, file=sys.stderr)
print(float(pred[0]))
