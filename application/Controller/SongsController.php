<?php

/**
 * Class SongsController
 * This is a demo Controller class.
 *
 * If you want, you can use multiple Models or Controllers.
 *
 * Please note:
 * Don't use the same name for class and method, as this might trigger an (unintended) __construct of the class.
 * This is really weird behaviour, but documented here: http://php.net/manual/en/language.oop5.decon.php
 *
 */

namespace APP\Controller;


class SongsController
{
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourproject/songs/index
     */
    public function index()
    {
        // Instance new Model (Song)
       /*  $Song = new Song();
        // getting all songs and amount of songs
        $songs = $Song->getAllSongs();
        $amount_of_songs = $Song->getAmountOfSongs(); */

       // load views. within the views we can echo out $songs and $amount_of_songs easily
        $this->header();
		$this->view('/songs/index');
		$this->footer();
    }

}
