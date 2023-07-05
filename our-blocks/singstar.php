<?php

require_once( SINGSTARPATH . 'inc/GetSongs.php' );
require_once ( SINGSTARPATH .  'inc/GetOrder.php' );
$getSongs = new GetSongs();
$getOrder = new GetOrder();

?>

  <h4>Search for a song</h4>
  <form action="#foundsongs" class="create-song-form" method="GET">
        
    <input type="text" class="textinput" name="songname" placeholder="name...">
    <input type="text" class="textinput" name="artist" placeholder="artist">
	  <input type="text" class="textinput" name="game" placeholder="game">
	  <button>Search</button>
  </form>
 <p> <div class="foundsongs" id="foundsongs">
	

		<?php 

			$songCount = $getSongs->count;
			if ($songCount == 0)
				echo (" No matching songs found. Showing all songs instead. ");
			else {
				echo (" Found <strong>" . $songCount . "</strong> song(s).");
			}
		?>

	<button class="resetbutton"><a class="resetbutton" href='<?php echo "?"; ?>'">Reset</a> </button> </div> </p>

  <table class="song-table" id="songlist">

  <tr>
	<th>
        <form action="#foundsongs" method="POST">
		<label><button name="asc">Name</button></label>
            <input type="hidden" name="column" value="songname">
            
        </form>
      </th>
      <th>
        <form action="#foundsongs" method="POST">
<label><button name="asc">Artist</button></label>
            <input type="hidden" name="column" value="artist">
        </form>
      </th>
      <th>
        <form action="#foundsongs" method="POST">
<label><button name="asc">Game</button></label>
            <input type="hidden" name="column" value="game">
        </form>
      </th>
      <?php if (current_user_can('administrator')) { ?>
        <th>Delete</th><tr>
		<?php } ?> </tr>
    <?php

$songTable = $getSongs->songs;

$column = $getOrder->orderColumn;
$direction = $getOrder->direction;


array_multisort( array_column( $songTable, $column ), $direction, SORT_STRING, $songTable );
      foreach($songTable as $song) { ?>
        <tr>
          <td><?php echo $song->songname; ?></td>
          <td><?php echo $song->artist; ?></td>
          <td><?php echo $song->game; ?></td>
          <?php if (current_user_can('administrator')) { ?>
            <td style="text-align: center;">
            <form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" method="POST">
              <input type="hidden" name="action" value="deletesong">
              <input type="hidden" name="idtodelete" value="<?php echo $song->id; ?>">
              <button class="delete-song-button">X</button>
            </form>
          </td>
          <?php } ?>
        </tr>
      <?php }
    ?>
  </table>

  <?php 
    if (current_user_can('administrator')) { ?>
  <h4>Enter new song info</h4>
      <form action="<?php echo esc_url(admin_url('admin-post.php')) ?>" class="create-song-form" method="POST">
        <input type="hidden" name="action" value="createsong">
        <input type="text" class="textinput" name="incomingsongname" placeholder="name...">
        <input type="text" class="textinput" name="incomingartistname" placeholder="artist">
        <input type="text" class="textinput" name="incominggamename" placeholder="game">
        <button>Add Song</button>
      </form>
    <?php }
  ?>