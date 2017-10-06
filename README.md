# PHP Report Generator
A time-saving PHP reporting library.

_NOTE: This package is currently alpha and unstable. As we use it and settle on a final set of features for first release, we'll look to make 1.0.0 our first stable tag._

## Installation

Install composer in a common location or in your project:

```bash
curl -s http://getcomposer.org/installer | php
```

Create the composer.json file as follows:

```json
{
    "require": {
        "rootwork/php-report-generator": "0.1.0"
    }
}
```

Run the composer installer:

```bash
php composer.phar install
```

## Concepts
This library is designed to give developers a way to encapsulate reports with a single class.

### Core Methods
Your report class should extend `Rootwork\Report\ReportAbstract` and implement `Rootwork\Report\ReportInterface`. This will give you two abstract public methods to implement in your class: `define()` and `run()`.

### Definition, Columns and Variables
All reports have a `Definition` object the defines the features of the report. You add `Column` instances to define your columns and optional `Variable` instances if you want to pass variable parameters to your report (for an SQL `WHERE` clause for example).

`Column($name, $display, $type, [$format = null, $total = false])`

`Variable($name, $display, $type, [$default = null, array $options = [], $format = null])`

### Data Sources
This library is currently data source agnostic. You are able to pass in and use any data source you like so long as you set an array of `rows` at the end of your `run()` method.

You may pass data sources and other dependencies to the constructor using the `$options` array, and use them in the `initialize()` method. The constructor will call `initialize()` with the `$options` array. Overriding `__construct` is discouraged. 

## Usage

### Example Report Class

```php
<?php
use Rootwork\Report\ReportAbstract;
use Rootwork\Report\ReportInterface;
use Rootwork\Report\Column;
use Rootwork\Report\Variable;

class MyReport extends ReportAbstract implements ReportInterface
{
    
    /**
     * @var PDO $pdo
     */
    protected $pdo;

    /**
     * Initialize the report class with any custom dependencies you need.
     *
     * @param array $options
     */
    public function initialize(array $options = [])
    {
        $this->pdo = $options['pdo'];
    }

    /**
     * Method for setting up the report definition.
     */
    protected function define()
    {
        $salesReps = [
            'tstark'  => 'Tony Stark',
            'bbanner' => 'Bruce Banner',
            'nfury'   => 'Nick Fury',
        ];
        
        $this->getDefinition()
            ->setTitle('My Sales Report')
            ->addColumn(new Column(
                'orderId', 'Order ID', Column::TYPE_INTEGER, Column::FORMAT_NUMBER
            ))
            ->addColumn(new Column(
                'date', 'Date', Column::TYPE_DATE, 'Y-m-d'
            ))
            ->addColumn(new Column(
                'customer', 'Customer', Column::TYPE_STRING
            ))
            ->addColumn(new Column(
                'salesRep', 'Sales Rep', Column::TYPE_STRING
            ))
            ->addColumn(new Column(
                'amount', 'Amount', Column::TYPE_FLOAT, Column::FORMAT_CURRENCY, true
            ))
            ->addVariable(new Variable(
                'startDate', 'Start Date', Variable::TYPE_DATE, date('Y-m-d'), [], 'Y-m-d'
            ))
            ->addVariable(new Variable(
                'salesRep', 'Sales Rep', Variable::TYPE_SELECT, 'tstark', $salesReps
            ));
    }

    /**
     * Run the report and return results.
     *
     * @return array[]
     */
    public function run()
    {
        $values = $this->getDefinition()->getVariableValues();
        $sql = "SELECT * FROM orders WHERE start_date >= :startDate AND sales_rep = :salesRep";
        $sth = $this->pdo->prepare($sql);
        $sth->execute($values);
        $results = $sth->fetchAll(PDO::FETCH_ASSOC);
        $rows = [];
        
        foreach ($results as $result) {
            $row = [
                'orderId'  => $result['order_id'],
                'date'     => date('Y-m-d', strtotime($result['date'])),
                'customer' => $result['customer'],
                'salesRep' => $result['sales_rep'],
                'amount'   => number_format($result['amount'], 2, ''),
            ];
            $rows[] = $row;
        }
        
        // IMPORTANT: You must set $this->rows as an associative array of results
        $this->rows = $rows;
        return $this->rows;
    }
}
```

### Using Report Classes
You can serialize the report definition as JSON. This is useful if you want to auto generate forms for running reports.

```php
$report = new MyReport(['pdo] => $pdo]);
$definition = $report->getDefinition();
echo json_encode($definition);
```

Result:
```json
{
    "title": "My Sales Report",
    "columns": [
        {
            "name": "orderId",
            "display": "Order ID",
            "type": "integer",
            "format": "number",
            "total": false
        },
        {
            "name": "date",
            "display": "Date",
            "type": "date",
            "format": "Y-m-d",
            "total": false
        },
        {
            "name": "customer",
            "display": "Customer",
            "type": "string",
            "format": null,
            "total": false
        },
        {
            "name": "salesRep",
            "display": "Sales Rep",
            "type": "string",
            "format": null,
            "total": false
        },
        {
            "name": "amount",
            "display": "Amount",
            "type": "float",
            "format": "currency",
            "total": true
        }
    ],
    "variables": [
        {
            "name": "startDate",
            "display": "Start Date",
            "type": "date",
            "default": "2017-10-03",
            "options": [],
            "format": "Y-m-d"
        },
        {
            "name": "salesRep",
            "display": "Sales Rep",
            "type": "select",
            "default": "tstark",
            "options": {
                "tstark": "Tony Stark",
                "bbanner": "Bruce Banner",
                "nfury": "Nick Fury"
            },
            "format": null
        }
    ]
}
```

The `run()` method will return report rows but a more useful output is to encode the report as JSON.

```php
$report = new MyReport(['pdo] => $pdo]);
$report->setParameters(['startDate' => '2017-01-01', 'salesRep' => 'bbanner']);
$report->run();
echo json_encode($report);
```

Result:
```json
{
    "title": "My Sales Report",
    "columns": [
        {
            "name": "Order ID",
            "type": "integer",
            "format": "number"
        },
        {
            "name": "Date",
            "type": "date",
            "format": "Y-m-d"
        },
        {
            "name": "Customer",
            "type": "string",
            "format": null
        },
        {
            "name": "Sales Rep",
            "type": "string",
            "format": null
        },
        {
            "name": "Amount",
            "type": "float",
            "format": "currency"
        }
    ],
    "rows": [
        {
            "orderId": 1,
            "date": "2017-01-14",
            "customer": "S.H.I.E.L.D.",
            "salesRep": "bbanner",
            "amount": "1000.00"
        },
        {
            "orderId": 2,
            "date": "2017-02-03",
            "customer": "US DOD",
            "salesRep": "bbanner",
            "amount": "2500.00"
        },
        {
            "orderId": 3,
            "date": "2017-03-23",
            "customer": "Acme, Inc.",
            "salesRep": "bbanner",
            "amount": "599.00"
        }
    ],
    "totals": [
        null,
        null,
        null,
        null,
        "4099.00"
    ]
}
```
