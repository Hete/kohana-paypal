PayPal module for [KohanaPHP](http://github.com/shadowhand/kohana) v3

This module have been redesigned in a morelikely Kohana way. I have implemented a factory method to build specific PayPal request object.

If you want to build a class for a request, you have to make a new class extending PayPal. You have also to implement a required() method which returns an key-tree array against which the request's parameters will be validated.

I need help to build request files to eventually support the whole PayPal NVP API.
