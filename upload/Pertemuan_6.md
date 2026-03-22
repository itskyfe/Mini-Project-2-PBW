# PERTEMUAN 6
PENGANTAR MACHINE LEARNING

## 🎯 Tujuan Pembelajaran

Setelah mengikuti pertemuan ini, mahasiswa diharapkan mampu:

1.  Memahami konsep dasar Machine Learning\
2.  Melakukan preprocessing data untuk model ML\
3.  Membangun model klasifikasi, clustering, dan regresi\
4.  Mengevaluasi performa model Machine Learning

------------------------------------------------------------------------

# IMPORT LIBRARY

``` python
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

from sklearn.model_selection import train_test_split
from sklearn.preprocessing import LabelEncoder, StandardScaler, MinMaxScaler

from sklearn.neighbors import KNeighborsClassifier
from sklearn.cluster import KMeans
from sklearn.ensemble import RandomForestRegressor

from sklearn.metrics import accuracy_score, classification_report
from sklearn.metrics import silhouette_score
from sklearn.metrics import mean_squared_error, r2_score
```
------------------------------------------------------------------------

# DATA TRANSFORMATION 

## ENCODING

``` python
categorical_cols = ["Nama Kolom"]

le = LabelEncoder()

for col in categorical_cols:
    df[col] = le.fit_transform(df[col])
```

## MAPPING

``` python
df['Nama Kolom'] = df['Nama Kolom'].map({
    'Nilai1': 0,
    'Nilai2': 1
})
```

------------------------------------------------------------------------

# SPLITTING DATA

``` python
X = df.drop("Kolom Target", axis=1)
y = df["Kolom Target"]

X_train, X_test, y_train, y_test = train_test_split(
    X,
    y,
    test_size=persentase data test,
    random_state=42
)
```

------------------------------------------------------------------------

#  SCALLING

## NORMALISASI

``` python
scaler = MinMaxScaler()

X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)
```

## STANDARISASI

``` python
scaler = StandardScaler()

X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)
```

------------------------------------------------------------------------

# FUNGSI MEMILIH FITUR

``` python
feature_names = X_train.columns

def get_feature_indices(selected_features):
    return [feature_names.get_loc(col) for col in selected_features]
```

------------------------------------------------------------------------

# KNN (K-NEAREST NEIGHBOR)

## Build Model

``` python
knn_features = ["Kolom Fitur"]

idx = get_feature_indices(knn_features)

X_train_knn = X_train_scaled[:, idx]
X_test_knn = X_test_scaled[:, idx]

knn = KNeighborsClassifier(n_neighbors=5)

knn.fit(X_train_knn, y_train)

y_pred_knn = knn.predict(X_test_knn)
```

## Evaluasi Model

``` python
accuracy = accuracy_score(y_test, y_pred_knn)

print("Accuracy:", accuracy)
print(classification_report(y_test, y_pred_knn))
```

------------------------------------------------------------------------

# K-MEANS (CLUSTERING)

## Elbow Method

``` python
inertia = []
K_range = range(1,11)

for k in K_range:
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(X_train_scaled)
    inertia.append(kmeans.inertia_)

plt.plot(K_range, inertia, marker='o')
plt.xlabel("Jumlah Cluster")
plt.ylabel("Inertia")
plt.title("Elbow Method")
plt.show()
```

------------------------------------------------------------------------

# RANDOM FOREST REGRESSION

## Build Model

``` python
rf_features = ["Kolom Fitur"]

idx = get_feature_indices(rf_features)

X_train_rf = X_train_scaled[:, idx]
X_test_rf = X_test_scaled[:, idx]

rf = RandomForestRegressor(
    n_estimators=200,
    random_state=42
)

rf.fit(X_train_rf, y_train)

y_pred_rf = rf.predict(X_test_rf)
```

## Evaluasi Model

``` python
mse = mean_squared_error(y_test, y_pred_rf)
rmse = np.sqrt(mse)
r2 = r2_score(y_test, y_pred_rf)

print("MSE:", mse)
print("RMSE:", rmse)
print("R2 Score:", r2)
```