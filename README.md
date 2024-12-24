# Installation

1. Clone repo inside your localhost folder

```
https://github.com/danielwattimuryjr/sistem-penerimaan-karyawan.git
```

2. Import the database and table from the [SQL file](./database.sql)

3. Run this command to generate the upload folder for the cv and the poster

```
> cd sistem-penerimaan-karyawan
> mkdir -p assets/uploads/cv
> chmod -R 0777 assets/uploads/cv
> mkdir -p assets/uploads/poster
> chmod -R 0777 assets/uploads/poster
```

4. Open your browser, and navigate to :
   [http://localhost/sistem-penerimaan-karyawan](http://localhost/sistem-penerimaan-karyawan)
