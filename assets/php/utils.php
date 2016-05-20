<?php
  
  // Development prefix for build
  $GLOBALS['prefix'] = '/build';

  // Convert object to a XML node
  function xml($xml, $schema, $data = []) {
    $id = isset($data['id']) ? $data['id'] : "";
    $exists = false;
    foreach ($xml as $value) {
      if ($id == $value['id']) {
        $exists = $value;
        break;
      }
    }

    if ($exists === false) {
      $content = $xml->addChild($schema['_type']);
      $content->addAttribute('id', $id);
    } else
      $content = $exists;

    foreach ($schema as $key => $value) {
      if ($key == '_type') continue;
      if (isset($content->$key))
        $content->$key = isset($data[$key]) ? $data[$key] : '';
      else
        $content->addChild($key, isset($data[$key]) ? $data[$key] : '');
    }

    return $xml;
  }

  // Load data from the XML database
  function load($name, $store) {
    if (!file_exists(__DIR__ . $store)) return;
    return simplexml_load_file(__DIR__ . $store);
  }

  // Save data to the XML database
  function save($name, $store, $schema, $data = [], $multi = false) {
    if (!file_exists(__DIR__ . $store))
      $xml = new SimpleXMLElement("<$name></$name>");
    else
      $xml = simplexml_load_file(__DIR__ . $store);

    if ($multi) {
      foreach ($data as $value)
        $xml = xml($xml, $schema, $value);
    } else
      $xml = xml($xml, $schema, $data);

    file_put_contents(__DIR__ . $store, $xml->asXML());
  }

  // Delete an XML node
  function delete($name, $store, $id) {
    if (!file_exists(__DIR__ . $store)) return;
    
    $xml = simplexml_load_file(__DIR__ . $store);
    
    foreach ($xml as $value) {
      if ($id == $value['id']) {
        $dom = dom_import_simplexml($value);
        $dom->parentNode->removeChild($dom);
      }
    }

    file_put_contents(__DIR__ . $store, $xml->asXML());
  }

  // Add contents to a div
  function div($content, $class = '') {
    return '<div class="' . $class . '">' . $content .'</div>';
  }

  // Create a HTML form from schema
  function form($action, $schema, $data = [], $text = 'Lähetä') {
    $final = "<form method='post' action='" . $GLOBALS['prefix'] ."/router.php' class='" . $action . "'>";
    $final .= "<input id='_action' name='_action' type='hidden' value='$action'></input>";

    if ($action == 'delete') {
      if (!isset($data['id'])) return;
      $id = $data['id'];
      $final .= "<input id='id' name='id' type='hidden' value='$id'></input>";
      $final .= "<input id='_type' name='_type' type='hidden' value='" . $schema['_type'] . "'></input>";
      return $final . "<button name='submit' type='submit'>$text</button></form>";
    }

    foreach ($schema as $key => $type) {
      if ($key == '_type') {
        $final .= "<input id='$key' name='$key' type='hidden' value='$type'></input>";
        continue;
      }

      $value = is_object($data) ? $data->$key : (isset($data[$key]) ? $data[$key] : '');
      switch ($type) {
        case 'text':
        default:
          $final .= div("<label for='$key'>$key</label><input id='$key' name='$key' type='text' value='$value'></input>", 'input');
          break;
        
        case 'description':
          $final .= div("<label for='$key'>$key</label><textarea id='$key' name='$key'>$value</textarea>", 'input');
          break;

        case 'number':
          $final .= div("<label for='$key'>$key</label><input id='$key' name='$key' type='number' step='0.01' value='$value'></input>", 'input');
          break;

        case 'hidden':
          $final .= "<input id='$key' name='$key' type='hidden' value='$value'></input>";
          break;
      }
    }

    return $final . "<button name='submit' type='submit'>$text</button></form>";
  }

  function modal($id, $title, $data, $filter = null) {
    $final = "<div id='$id' class='modal fade' role='dialog'><div class='modal-dialog'><div class='modal-content'>";

    $final .= "<div class='modal-header'><button type='button' class='close' data-dismiss='modal'>&times;</button><h4 class='modal-title'>$title</h4></div>";

    $final .= "<div class='modal-body'><table class='table'>";

    for ($x = 0; $x < ($data == null ? 0 : $data->count()); $x++) {
      if ($x == 0) {
        $final .= "<tr>";
        foreach ($data[$x] as $key => $value) {
          if ($key == 'id' || $key == 'kategoria' || $key == 'kuvaus') continue;
          $final .= "<th>$key</th>";
        }

        $final .= "<th></th>";

        $final .= "</tr>";
      }

      if ($filter != null && $data[$x]->kategoria != $filter) continue;

      $final .= "<tr>";

      foreach ($data[$x] as $key => $value) {
        if ($key == 'id' || $key == 'kategoria' || $key == 'kuvaus') continue;
        $final .= "<td>$value</td>";
      }

      $final .= "<td><a href='#'>Lisätiedot</a></td>";

      $final .= "</tr>";
    }

    $final .= "</table></div>";

    $final .= "<div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>Sulje</button></div>";

    return $final . "</div></div></div>";
  }

  function toKebabCase($value) {
    return preg_replace('/[^a-ö0-9-]+/', '', implode('-', explode(' ', strtolower($value))));
  }
?>