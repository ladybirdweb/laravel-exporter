# Laravel Exporter

Efficiently Export Datatable Data In CSV,PDF Format.

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ladybirdweb/laravel-exporter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ladybirdweb/laravel-exporter/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/ladybirdweb/laravel-exporter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ladybirdweb/laravel-exporter/build-status/master)
[![StyleCI](https://github.styleci.io/repos/196688771/shield?branch=master)](https://github.styleci.io/repos/196688771)
[![Build Status](https://travis-ci.org/ladybirdweb/laravel-exporter.svg?branch=master)](https://travis-ci.org/ladybirdweb/laravel-exporter)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/ladybirdweb/laravel-exporter/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## Installation

Via Composer

``` bash
$ composer require lws/exportactions
```

## Usage

This Package Exports the API Data in CSV,PDF Format.You just have to pass ```url``` of the API whose data needs to be exported and ```format``` you wish to export to (CSV,PDF).

After installation you need to add the following line to config/app.php (No need in case Laravel > 5.5) -

```
'providers' => [
/*
     * Package Service Providers...
     */
    LWS\ExportActions\ExportServiceProvider::class,
    LWS\ExportActions\CustomEventServiceProvider::class,
]
```

You need migrate the database using

``` bash
$ php artisan migrate
```

Change QUEUE_CONNECTION entry in .env file

```
QUEUE_CONNECTION=database
```


This package sets up three routes
* ```/export```- Accepts ```url``` and ```format``` to Export.
* ```/progress``` - Return Progress of Export operation taking long time (usually PDF files),running in Queue.
* ```/download/{id}``` - After the Export File Processed in background,You can download the file by passing the job id (id of the job can be found in ```/progress```).

Typical Call to the API to export Data in CSV Format:

```
this.axios({
  method: "POST",
  data: {
    url: 'https://jsonplaceholder.typicode.com/photos', //API url from which you are exporting
    format:"csv"
  },
  url: "http://localhost:8000/export", 
  responseType: 'arraybuffer'
})
```
__**Note**__: This Package return downloadable file as response. i.e If the frontend is running on different PORT the frontend has to take care of making the download in browser.

