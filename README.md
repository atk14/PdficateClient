Pdficate Client
===============

This is a client for [Pdficate.com - HTML to PDF converter](https://pdficate.com/). The client is written in PHP.

## 1. Usage

In the configuration file set the PDFICATE_API_KEY constant.

    define("PDFICATE_API_KEY","123.abcdefghijklmnopqrst");

In order to obtain a PDFICATE_API_KEY you have to register at https://pdficate.com/en/users/create_new/. At the moment the Pdficate is closed beta. So you need an invitation code in order to get the key.

    $pdficate = new \Pdficate\Client();
    $filename = $pdficate->printToPdf("https://www.atk14.net/");

An exception with a proper message is thrown when something went wrong.

## 2. Installation

Use the Composer to install the Texmit Client.

    cd path/to/your/project/
    composer require atk14/pdficate-client dev-master

## 3. Licence

Pdficate Client is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)

[//]: # ( vim: set ts=2 et: )
