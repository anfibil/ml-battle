import numpy as np 
from sklearn.datasets import load_iris

data = load_iris()
X = data.data
y = data.target

np.save('../src/datasets/IRIS.x.npy', np.array(X))
np.save('../src/datasets/IRIS.y.npy', np.array(y))
