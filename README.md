# matrixalgebra
Matrix Algebra class that performs all the basic matrix algebraic operations
## Usage
```php
<?php
require_once('matrix.php');
header("Content-type: text/plain");
$matrix = new Matrix();
$a = $matrix->create('[1 2 3;4 5 6;7,8,9]');
// Let's see A
$matrix->plot($a,"A matrix");
$b = $matrix->create('[9 8 7;6 5 4;3 2 1]');
// Let's see B
$matrix->plot($b,"B matrix");
// Now suma A and B
$result = $matrix->sum($a, $b);
// Let's see result
$matrix->plot($result,"A + B matrix");
// Determinant of A
echo "Matrix A determinant: " . $matrix->det($a) . "\n\r";
// Solve Gauss Jordan
$matrix->plot($matrix->solve($a,$b));
```
