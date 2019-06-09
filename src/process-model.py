from joblib import load
from sklearn.metrics import r2_score
import json
import numpy as np
import sys
import traceback

data = {}
data['error'] = ''
data['accuracy'] = 0
data['modelName'] = ""

try:
    # Load the model
    model = load(sys.argv[1])
    
    # Load the dataset
    X_test = np.load('./datasets/'+ sys.argv[2] + '.x.npy')
    y_test = np.load('./datasets/'+ sys.argv[2] + '.y.npy')

    # Make predictions using the model we loaded
    y_pred = model.predict(X_test)

    # Checking if model being evaluated is a classifier or regression
    model_type = 'reg' if model.__class__.__name__ == 'LinearRegression' else 'clf'

    # Evaluate regression models using R^2
    if model_type == 'reg':
        data['accuracy'] = r2_score(y_test, y_pred)
    # Evaluate classification models using regular accuracy
    else:
        data['accuracy'] = float(np.average(y_test == y_pred)) * 100

    data['modelName'] = model.__class__.__name__

except:
    data['error'] = str(traceback.format_exc())

print(json.dumps(data));