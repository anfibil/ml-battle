# Amazing imports
import json
from joblib import load
import numpy as np
import traceback
import sys

data = {}
data['error'] = ''
data['accuracy'] = 0
data['modelName'] = ""

try:
    # Load the model
    clf = load(sys.argv[1])
    
    # Load the dataset
    X_test = np.load('./datasets/'+ sys.argv[2] + '.x.npy')
    y_test = np.load('./datasets/'+ sys.argv[2] + '.y.npy')

    # Make predictions using the model we loaded
    y_pred = clf.predict(X_test)

    # Evaluate predictions using simple "accuracy"
    data['accuracy'] = float(np.average(y_test == y_pred)) * 100
    data['modelName'] = clf.__class__.__name__

except:
    data['error'] = str(traceback.format_exc())

print(json.dumps(data));