<?php

function addSong() {

  return array(
    'songname' => trim($combined_name),
    'game' => $foods[array_rand($foods, 1)],
    'artist' => $hobbies[array_rand($hobbies, 1)]
  );
}