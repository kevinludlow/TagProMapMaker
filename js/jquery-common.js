// Initialize a click counter for the page.  We will
// use this to automatically save maps after a certain
// number of changes
var clickCounter = 0;

// Initialize an object for handling the button/switch editor
// We use an AJAX process to load these dynamically rather
// than rely on JSON or some other similar toolset for creating
// the object between the server and client codebases
var switchObject = {};
var bombObject = {};

// Initialize the brush size.  This has been a requested feature
// in order to make the painting of the map significantly
// easier (especially in the absence of fill tools which
// are inherently more difficult in JS.
var brushSize = 1;

// Handle functionality when the page loads
$(document).ready( function()
{
  // Handle when the user moves off of the map
  // Note that this is just for aesthetics
  $('.map').mouseleave( function()
  {
    $('#map_position_x').html( '0' );
    $('#map_position_y').html( '0' );

  }); // end jQuery map mouseout


  // Handle when the user moves over a cell
  $('.map td').mouseover( function()
  {
    // Get the CellID
    var CellID = $(this).find('.cell_id').val();

    // Get the x,y coordinates for the cell
    var CellXY = getCellXY( CellID );

    // Update the position
    $('#map_position_x').html( CellXY['x'] );
    $('#map_position_y').html( CellXY['y'] );

  }); // end map td mouseover


  // Handle when the user clicks on a map cell
  $('.map td').click( function()
  {
    // Increment the clickCounter
    clickCounter++;

    // Get the CellID and store it as an integer
    var CellID = parseInt( $(this).find( '.cell_id' ).val() );

    // Handle the click based on the current EditMode
    if( getEditMode() == 2 )
    {
      // When the map edit mode is 2, we will let the user
      // set the Map Switches
      
      // Get the BrushID
      var BrushID = $(this).find( '.cell_brush' ).val();

      if( BrushID == 7 )
      {
        // Any time a new button is clicked, we need to
        // first clear all of the button clicks
        clearHighlights();

        // The user has clicked on a button and so we will
        // set the button ID and highlight
        $('#ButtonCellID').val( CellID );

        // Create a new switchArray for this CellID
        if( ! switchObject[CellID] )
          switchObject[CellID] = new Array();

        // Create a new bombArray for this CellID
        if( ! bombObject[CellID] )
          bombObject[CellID] = new Array();

        // Toggle the highlights for this button
        highlightOn( CellID );

      } // end if BrushID = 7
      else if( BrushID == 8 || BrushID == 9 )
      {
        // The user has clicked on one of the two switch
        // grating types

        // Get the ButtonCellID
        var ButtonCellID = $('#ButtonCellID').val();

        // Check to see if a ButtonCellID has currently
        // already been set we can add the grating cell to
        // that array
        if( ButtonCellID > 0 )
        {
          // Get the current button being edited
          var ButtonCellID = $('#ButtonCellID').val();

          // Add the cell to the object array only if
          // it does not currently exist within the object array
          // otherwise we need to remove it
          if( switchObject[ButtonCellID].indexOf( CellID ) == -1 )
          {
            // The element was not found, add it
            switchObject[ButtonCellID].push( CellID );
          }
          else
          {
            // The element was found, remove it
            var pos = switchObject[ButtonCellID].indexOf( CellID );
            switchObject[ButtonCellID].splice( pos, 1 );

            // Turn the cell highlighting off
            highlightOff( CellID );
          }

        } // end if ButtonCellID > 0

      } // end else if BrushID 7 or 8
      else if( BrushID == 11 )
      {
        // The user has clicked on one of the bombs

        // Get the ButtonCellID
        var ButtonCellID = $('#ButtonCellID').val();

        // Check to see if a ButtonCellID has currently
        // already been set we can add the bomb cell to
        // that array
        if( ButtonCellID > 0 )
        {
          // Get the current button being edited
          var ButtonCellID = $('#ButtonCellID').val();

          // Add the cell to the object array only if
          // it does not currently exist within the object array
          if( bombObject[ButtonCellID].indexOf( CellID ) == -1 )
          {
            // The bomb was not found and so we will add it
            bombObject[ButtonCellID].push( CellID );
          }
          else
          {
            // The bomb was found so remove it
            var pos = bombObject[ButtonCellID].indexOf( CellID );
            bombObject[ButtonCellID].splice( pos, 1 );

            // Turn the cell highlighting off
            highlightOff( CellID );
          }

        } // end if ButtonCellID > 0

      } // end else if BrushID == 11

        // Toggle the highlights for this button
        highlightOn( CellID );

    } // end if EditMode = 2
    else
    {
      // The alternative value should be 1, but in all
      // other cases we will handle regular map painting

      // Get the current position of the Red and Blue
      // Flags (if they exist at all)
      var FlagRed = $('#FlagRed').val();
      var FlagBlue = $('#FlagBlue').val();

      // Get the current BrushID
      var BrushID = $('#BrushID').val();

      // Get the current BrushImage
      var BrushImage = $('#BrushImage').val();

      // With the addition of the varying brush sizes, we
      // will let the user paint multiple cells at once
      // if they are using one of the floor tiles
      if( BrushID == 1 || BrushID == 2 || BrushID == 3 || BrushID == 13 || BrushID == 14 )
        var cellRange = calculateCellRange( CellID, brushSize );
      else
        var cellRange = calculateCellRange( CellID, 1 );

      // Step through the cellRange and perform the necessary
      // calculations for each cell painting
      for( var i = 0; i < cellRange.length; i++ )
      {
        // Set a temporary cell for ease of use
        var tempCellID = cellRange[i];

        // Given the newly implemented switch/bomb editor modes
        // We need to determine if the user is removing an existing
        // button, grating, or bomb.  If so, we need to remove the 
        // object properties associated with this entity.
        var currentCellBrush = $('#BrushID_' + tempCellID ).val();

        if( currentCellBrush == 7 )
          removeButton( tempCellID );

        // If we are removing a flag, we need to account for this
        if( FlagRed == tempCellID )
          $('#FlagRed').val( 0 );

        if( FlagBlue == tempCellID )
          $('#FlagBlue').val( 0 );

        // Set the cell's BrushID
        $('#BrushID_' + tempCellID ).val( BrushID );

        // Now paint the cell with the brush
        $('.image_' + tempCellID ).attr( 'src', BrushImage );

      } // end for i = 0...

      // If we already have a flag set somewhere on the
      // board and are placing a new one, we need to account
      // for this case
      if( BrushID == 4 )
      {
        // Remove the red flag if it already exists
        if( FlagRed > 0 )
        {
          $('.image_' + FlagRed ).attr( 'src', 'theme/Default/images/icons/20/20_floor_blank.png' );
          $('#BrushID_' + FlagRed ).val( '3' );
        }

        // Update the new red flag position
        $('#FlagRed').val( CellID );
      }

      if( BrushID == 5 )
      {
        // Remove the blue flag if it already exists
        if( FlagBlue > 0 )
        {
          $('.image_' + FlagBlue ).attr( 'src', 'theme/Default/images/icons/20/20_floor_blank.png' );
          $('#BrushID_' + FlagBlue ).val( '3' );
        }

        // Update the new blue flag position
        $('#FlagBlue').val( CellID );
      }


    } // end else editMode...

    // If the clickCounter is a multiple of 10, let's
    // save the map so the user doesn't lose too many steps
    if( clickCounter % 10 == 0 )
    {
      // Get the MapID
      var MapID = $('#MapID').val();

      // Execute the map saving function
      saveMap( MapID );

      // Reset the clickCounter
      clickCounter = 0;
    }

  }); // end map td click


  // Handle when the user changes the map mode (to switch editor)
  $('#editSwitches').click( function()
  {
    // Toggle the switch editor mode
    if( getEditMode() == 1 )
    {
      setEditMode( 2 );
      $('#editSwitches').html( ' <span style="background-color: #ffff99;">Exit Switch/Bomb Editor</span>' );
    }
    else
    {
      setEditMode( 1 );
      $('#editSwitches').html( ' Enter Switch/Bomb Editor' );
    }

  }); // end jQuery editSwitches click


  // Handle when the user wants to reset all switches and bombs
  $('#resetSwitches').click( function()
  {
    var reset = confirm( 'If the switches/bombs have encountered a logical error, it may be necessary to reset them.\n\nIf you would like to reset all switch and bomb mappings to their respective buttons, press okay.  Note that you must still SAVE THE MAP in order for this affect to remain.' );

    // If the user wishes to do this we will reset the objects,
    // remove any current highlighting, and return the mapmode
    if( reset )
    {
      switchObject = {};
      bombObject = {};

      setEditMode(1);

      $('#editSwitches').html( ' Enter Switch/Bomb Editor' );

    }

  }); // end jQuery resetSwitches click


  // Handle when the user changes their brush
  $('.brush').click( function()
  {
    // Get the BrushID
    var BrushID = $(this).attr( 'rel' );

    // Change the brush
    changeBrush( BrushID );

  }); // end jQuery brush click


  // Handle when the user changes their brush size
  $('.brushRadius').click( function()
  {
    // Get the radius
    var radius = $(this).attr( 'rel' );

    // Set the radius
    brushSize = radius;

    // Unset all of the brushRadius images
    $('#brushSize1').attr( 'src', 'theme/Default/images/icons/brushsize/brush_1_off.png' );
    $('#brushSize2').attr( 'src', 'theme/Default/images/icons/brushsize/brush_2_off.png' );
    $('#brushSize3').attr( 'src', 'theme/Default/images/icons/brushsize/brush_3_off.png' );
    $('#brushSize4').attr( 'src', 'theme/Default/images/icons/brushsize/brush_4_off.png' );

    // Turn on the current brush
    $('#brushSize' + radius).attr( 'src', 'theme/Default/images/icons/brushsize/brush_' + radius + '_on.png' );

  }); // end jQuery function brushRadius click


  // Handle when the user manually saves the map
  $('#saveMap').click( function()
  {
    // Get the MapID
    var MapID = $('#MapID').val();

    // Execute the map saving function
    saveMap( MapID );

  }); // end jQuery saveme


  // Handle when the user edits the map name
  $('#editMapName').click( function()
  {
    // Get the current map name
    var MapName = $('#MapName').val();

    // Ask the user to set the map name
    var newMapName = prompt( "Name of Map:", MapName );

    // Update the map name 
    $('#MapName').val( newMapName );
    $('#mapDisplayName').html( newMapName );

  }); // end jQuery editMapName function


  // Handle when the user edits the map's author name
  $('#editMapAuthor').click( function()
  {
    // Get the current author name
    var MapAuthor = $('#MapAuthor').val();

    // Ask the user to set the map's author
    var newMapAuthor = prompt( "Name of Author:", MapAuthor );

    // Update the map's author 
    $('#MapAuthor').val( newMapAuthor );
    $('#mapDisplayAuthor').html( newMapAuthor );

  }); // end jQuery editMapName function


  // Handle the shortcut keypresses
  if( $.browser.mozilla )
  {
    $(document).keypress( checkKey );
  }
  else
  {
    $(document).keydown( checkKey );
  }


  // Once the page has loaded, we also need to
  // load the switch and bomb objects.  This could
  // be done using some kind of JSON method, but since
  // our dataset isn't particularly large, this provides
  // a simpler method to implement
  switchObject = eval( '(' + $('#switchString').val() + ')' );
  bombObject = eval( '(' + $('#bombString').val() + ')' );


}); // end Map document.ready


function changeBrush( BrushID )
{
  // Every time we change the brush, we want to
  // make sure that the edit mode is in map editor
  setEditMode( 1 );

  // Get the entity the user requested
  var BrushLink = $("a[rel='" + BrushID + "']");

  // Get the image icon
  var BrushImage = BrushLink.attr( 'rev' );

  // Update the hidden fields
  $('#BrushID').val( BrushID );
  $('#BrushImage').val( BrushImage );

  // Remove any instances of the selected brush class
  $('.brush').find( 'img' ).removeClass();

  // Swap out the brush selector styles
  BrushLink.find( 'img' ).addClass( 'selected' );

} // end function changeBrush()


function saveMap( MapID )
{
  // Start by displaying the "saving now" text
  $('#saveMapStatus').show();
  $('#saveMapText').hide();

  // Get the MapID
  var MapID = $('#MapID').val();

  // Get the current map name and author
  var MapName = $('#MapName').val();
  var MapAuthor = $('#MapAuthor').val();

  // Initialize the BrushIDString
  var BrushIDString = '';

  // Build the BrushID map
  for( i = 1; i <= 2500; i++ )
  {
    BrushIDString = BrushIDString + $('#BrushID_' + i ).val() + ',';
  }


  // Initialize the SwitchString
  var SwitchString = '';

  // Build the buttons/switches mapping
  for( var key in switchObject )
  {
    // Add the key to the SwitchString
    SwitchString = SwitchString + key;

    if( switchObject.hasOwnProperty( key ) )
    {

      // Step through each of the array elements
      for( i = 0; i < switchObject[key].length; i++ )
      {
        // Get the CellID from the array
        var tmpCell = switchObject[key][i];

        // Add the cell to the SwitchString
        SwitchString = SwitchString + ',' + tmpCell;

      } // end for i = 0

    } // end if switchObject.hasOwnProperty...

    // Separate the outer keys with hyphens
    SwitchString = SwitchString + '-';

  } // end for var key...


  // Initialize the BombString
  var BombString = '';

  // Build the bombs mapping
  for( var key in bombObject )
  {
    // Add the key to the BombString
    BombString = BombString + key;

    if( bombObject.hasOwnProperty( key ) )
    {

      // Step through each of the array elements
      for( i = 0; i < bombObject[key].length; i++ )
      {
        // Get the CellID from the array
        var tmpCell = bombObject[key][i];

        // Add the cell to the BombString
        BombString = BombString + ',' + tmpCell;
      
      } // end for i = 0

    } // end if bombObject.hasOwnProperty...

    // Separate the outer keys with hyphens
    BombString = BombString + '-';

  } // end for var key...
 

  // Run the AJAX request to load the dropdown table
  $.ajax(
  {
    url: "modules/Map/ajax.save.php",
    type: "post",
    data: "MapID=" + MapID + "&MapName=" + MapName + "&MapAuthor=" + MapAuthor + "&BrushIDString=" + BrushIDString + "&SwitchString=" + SwitchString + "&BombString=" + BombString,

    success: function( html )
    {
      // Return the Save Now link
      $('#saveMapText').show();
      $('#saveMapStatus').hide();

    } // end AJAX success call

  }); // end jQuery AJAX call

}


// Handle the keyboard shortcuts
function checkKey( e )
{
  switch( e.which )
  {
    case 49:
      changeBrush( 1 );
      break;

    case 50:
      changeBrush( 2 );
      break;

    case 51:
      changeBrush( 3 );
      break;

    case 113:
      changeBrush( 4 );
      break;

    case 119:
      changeBrush( 5 );
      break;

    case 101:
      changeBrush( 6 );
      break;

    case 97:
      changeBrush( 7 );
      break;

    case 115:
      changeBrush( 8 );
      break;

    case 100:
      changeBrush( 9 );
      break;

    case 122:
      changeBrush( 10 );
      break;

    case 120:
      changeBrush( 11 );
      break;

    case 99:
      changeBrush( 12 );
      break;

  } // end switch

} // end function checkKey()


function getEditMode()
{
  // Get the edit mode from the hidden key
  var EditMode = $('#EditMode').val();

  // Return the current edit mode to the user
  return EditMode;

} // end function getEditMode()


function setEditMode( mode )
{
  // Set the current edit mode
  $('#EditMode').val( mode );

  // If we are turning off the switch editor
  // we need to reset the ButtonCellID and make
  // sure that any highlighted fields are
  // returned to their normal state
  if( mode == 1 )
  {
    $('#ButtonCellID').val( 0 );
    clearHighlights();
  }

  // Exit the function gracefully
  return 0;

} // end function setEditMode()


// Step through all of the elements of the switchObject
function highlightOn( CellID )
{
  // The user has clicked on a button and so we will
  // set the button ID and highlight
  var ButtonCellID = parseInt( $('#ButtonCellID').val() );

  // Ensure that the button cell is highlighted
  $('.image_' + ButtonCellID ).attr( 'src', "theme/Default/images/icons/20/20_button_highlight.png" );

  // Make sure that the ButtonCellID exists in the object
  if( switchObject[ButtonCellID] )
  {
    // Step through the switches and toggle the highlights
    for( i = 0; i < switchObject[ButtonCellID].length; i++ )
    {
      // Get the CellID from the array
      var tmpCell = switchObject[ButtonCellID][i];

      // Fill in the grating overlays
      if( $('#BrushID_' + tmpCell ).val() == 8 )
        $('.image_' + tmpCell ).attr( 'src', "theme/Default/images/icons/20/20_floor_switch_off_highlight.png" );
      else if( $('#BrushID_' + tmpCell ).val() == 9 )
        $('.image_' + tmpCell ).attr( 'src', "theme/Default/images/icons/20/20_floor_switch_green_highlight.png" );

    } // end for i (grates)

  } // end if switchObject...

  // Make sure that the ButtonCellID exists for this object too
  if( bombObject[ButtonCellID] )
  {
    // Step through the bombs and toggle the highlights
    for( i = 0; i < bombObject[ButtonCellID].length; i++ )
    {
      // Get the CellID from the array
      var tmpCell = bombObject[ButtonCellID][i];

      // Fill the bomb overlay
      $('.image_' + tmpCell ).attr( 'src', "theme/Default/images/icons/20/20_bomb_highlight.png" );

    } // end for i (bombs)

  } // end if bombObject...

} // end function highlightOn()


// Turn off the cell for a single element
function highlightOff( CellID )
{
  // Fill in the grating overlays
  if( $('#BrushID_' + CellID ).val() == 8 )
    $('.image_' + CellID ).attr( 'src', "theme/Default/images/icons/20/20_floor_switch_off.png" );
  else if( $('#BrushID_' + CellID ).val() == 9 )
    $('.image_' + CellID ).attr( 'src', "theme/Default/images/icons/20/20_floor_switch_green.png" );
  else if( $('#BrushID_' + CellID ).val() == 11 )
    $('.image_' + CellID ).attr( 'src', "theme/Default/images/icons/20/20_bomb.png" );

} // end function highlightOff()


// Clear all of the highlighted cells on the page.  
// This is used when switching between the various buttons.
function clearHighlights()
{
  // Step over all of the switchObject keys
  for( var key in switchObject )
  {
    if( switchObject.hasOwnProperty( key ) )
    {
      // De-highlight the button
      $('.image_' + key ).attr( 'src', "theme/Default/images/icons/20/20_button.png" );

      // Step through each of the array elements
      for( i = 0; i < switchObject[key].length; i++ )
      {
        // Get the CellID from the array
        var tmpCell = switchObject[key][i];

        // Fill in the grating overlays
        if( $('#BrushID_' + tmpCell ).val() == 8 )
          $('.image_' + tmpCell ).attr( 'src', "theme/Default/images/icons/20/20_floor_switch_off.png" );
        else if( $('#BrushID_' + tmpCell ).val() == 9 )
          $('.image_' + tmpCell ).attr( 'src', "theme/Default/images/icons/20/20_floor_switch_green.png" );

      } // end for i = 0

    } // end if switchObject.hasOwnProperty...

  } // end for var key...

  // Step over all of the bombObject keys
  for( var key in bombObject )
  {
    if( bombObject.hasOwnProperty( key ) )
    {
      // Step through each of the array elements
      for( i = 0; i < bombObject[key].length; i++ )
      {
        // Get the CellID from the array
        var tmpCell = bombObject[key][i];

        // Fill the bomb overlay
        $('.image_' + tmpCell ).attr( 'src', "theme/Default/images/icons/20/20_bomb.png" );

      } // end for i = 0

    } // end if bombObject.hasOwnProperty...

  } // end for var key...

} // end function clearHighlights()


function removeButton( CellID )
{
  // Remove the button object
  delete switchObject[CellID];
  delete bombObject[CellID];

} // end function removeButton()



function calculateCellRange( CellID, radius )
{
  // Initialize the return array of cells
  var cellArray = new Array();

  // Start by getting the cell center
  var C = getCellXY( CellID );

  // Calculate the distance of the mathematical
  // radius by the current global brushsize
  var r = radius - 1;

  // Step through the potential X coordinates
  for( var x = C['x'] - r; x <= C['x'] + r; x++ )
  {

    // Step through the potential Y coordinates
    for( var y = C['y'] - r; y <= C['y'] + r; y++ )
    {

      // If both the x and y coordinate are within the
      // boundaries, we will calculate the cell position
      // and add it to the return array
      if( x >= 0 && x <= 49 && y >= 0 && y <= 49 )
      {
        // Calculate the CellID
        var CellID = getXYCell( x, y );

        // Add the CellID to the return array
        cellArray.push( CellID );

      } // end if...
       
    } // end for y

  } // end for x

  // Return the array of cells to be used
  return cellArray;


} // end function calculateCellRange()


function getCellXY( CellID )
{
  // Initialize the coordinate grid
  var C = new Array();

  C['y'] = Math.floor( ( CellID  - 1 ) / 50 );
  C['x'] = ( CellID - 1 ) - ( C['y'] * 50 );

  return C;

} // end function getCellXY()


function getXYCell( x, y )
{
  var CellID = (y * 50 ) + 1 + x;

  return CellID;

} // end function getXYCell()
