from sklearn.datasets import load_iris
from joblib import dump
from sklearn.linear_model import LogisticRegression

data = load_iris()
X = data.data
y = data.target

clf_lr = LogisticRegression()
clf_lr.fit(X, y)
dump(clf_lr, 'example-model.joblib')