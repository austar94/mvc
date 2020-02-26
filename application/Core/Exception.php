<?php

/**
 * Class Error
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */

// namespace APP\Core;

// class Exception
// {
//     protected $message = 'Unknown exception';   // exception message
//     private   $string;                          // __toString cache
//     protected $code = 0;                        // user defined exception code
//     protected $file;                            // source filename of exception
//     protected $line;                            // source line of exception
//     private   $trace;                           // backtrace
//     private   $previous;                        // previous exception if nested exception

//     public function __construct($message = null, $code = 0, Exception $previous = null);

//     final private function __clone();           // Inhibits cloning of exceptions.

//     final public  function getMessage();        // message of exception
//     final public  function getCode();           // code of exception
//     final public  function getFile();           // source filename
//     final public  function getLine();           // source line
//     final public  function getTrace();          // an array of the backtrace()
//     final public  function getPrevious();       // previous exception
//     final public  function getTraceAsString();  // formatted string of trace

//     // Overrideable
//     public function __toString();               // formatted string for display
// }