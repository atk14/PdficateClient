Pdficate Client
===============

This is a client for [Pdficate.com - URL to PDF converter & screenshoter](https://pdficate.com/). The client is written in PHP.

## 1. Usage

In the configuration file set the PDFICATE_API_KEY constant.

    define("PDFICATE_API_KEY","123.abcdefghijklmnopqrst");

In order to obtain a PDFICATE_API_KEY you have to register at https://pdficate.com/en/users/create_new/. At the moment the Pdficate is closed beta. So you need an invitation code in order to get the key.

    $client = new Pdficate\Client();
    $filename = $client->printToPdf("https://www.atk14.net/");

An exception with a proper message is thrown when something went wrong.

The are some optional options in the constructor.

    $client = new Pdficate\Client([
      "page_size" => "A4", // A4, A3, Letter

      "margin_top" => "2cm",
      "margin_right" => "2cm",
      "margin_bottom" => "2cm",
      "margin_left" => "2cm",

      "delay" => 0, // ms, the delay before printing to ensure that the page is fully loaded, intended for pages with a JS loading effect and so on
    ]);

There is also a screenshoter.

    $screenshoter = new Pdficate\Client\Screenshoter([
      "width" => 1024,
      "height" => 768,

      // the offset setting has no effect on the final image size specified in the width and height options
      "offset_top" => 0,
      "offset_right" => 0,
      "offset_bottom" => 0,
      "offset_left" => 0,
      
      "delay" => 0, // ms, the delay before printing to ensure that the page is fully loaded, intended for pages with a JS loading effect and so on

      "image_format" => "jpeg", // "png", "jpeg"
    ]);
    $filename = $screenshoter->screenshot("https://www.atk14.net");

## 2. Installation

Use the Composer to install the Texmit Client.

    cd path/to/your/project/
    composer require atk14/pdficate-client

## 3. Licence

Pdficate Client is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)

[//]: # ( vim: set ts=2 et: )
