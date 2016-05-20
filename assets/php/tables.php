<?php
  
  include 'php/utils.php';

  // A basic XML database table to be extended from
  class Table {
    public static $name = 'tables';
    public static $single = 'table';
    public static $store = '/../xml/table.xml';

    public static function form($action) {
      switch ($action) {
        case 'create':
        default:
          return '<div class="form">' . form($action, static::$schema, [], 'Luo ' . static::$single) . '</div>';

        case 'update':
          $data = self::get();
          if (empty($data)) return;
          $data = $data->{ static::$single };

          if ($data->count() <= 0) return;

          if ($data->count() == 1) {
            $final = '<div class="form">' . form($action, static::$schema, $data, 'Päivitä');
            $final .= form('delete', static::$schema, $data, 'Poista') . '</div>';
            return $final;
          }
          
          $final = '';
          for ($x = 0; $x < $data->count(); $x++) {
            $final .= '<div class="form">' . form($action, static::$schema, $data[$x], 'Päivitä');
            $final .= form('delete', static::$schema, $data[$x], 'Poista') . '</div>';
          }
          return $final;
      }
    }

    public static function get() {
      return load(static::$name, static::$store);
    }

    public static function show() {
      $data = self::get();
      if (empty($data)) return null;
      return $data->{ static::$single };
    }

    public static function update($data, $multi = false) {
      return save(static::$name, static::$store, static::$schema, $data, $multi);
    }

    public static function delete($id) {
      return delete(static::$name, static::$store, $id);
    }

    public static function route($route, $data) {
      switch ($route) {
        case 'create':
          $data['id'] = uniqid(static::$single . '-');
          return static::update($data);

        case 'update':
          return static::update($data);

        case 'delete':
          if (!isset($data['id'])) return;
          return static::delete($data['id']);
        
        default:
          return;
      }
    }

    public static $schema = [ '_type' => 'table' ];
  }

  // Database XML table for "Palvelut"
  class Palvelu extends Table {
    public static $name = 'palvelut';
    public static $single = 'palvelu';
    public static $store = '/../xml/palvelut.xml';

    public static $schema = [
      'id' => 'hidden',
      'nimi' => 'text',
      'kategoria' => 'text',
      'kuvaus' => 'description',
      'hinta' => 'text',
      'hoitaja' => 'text',

      '_type' => 'palvelu'
    ];
  }

  // Database XML table for "Tapahtumat"
  class Tapahtuma extends Table {
    public static $name = 'tapahtumat';
    public static $single = 'tapahtuma';
    public static $store = '/../xml/tapahtumat.xml';

    public static $schema = [
      'id' => 'hidden',
      'nimi' => 'text',
      'kuvaus' => 'description',

      '_type' => 'tapahtuma'
    ];
  }

  // Database XML table for "Hoitajat"
  class Hoitaja extends Table {
    public static $name = 'hoitajat';
    public static $single = 'hoitaja';
    public static $store = '/../xml/hoitajat.xml';

    public static $schema = [
      'id' => 'hidden',
      'nimi' => 'text',
      'kuva' => 'image',

      '_type' => 'hoitaja'
    ];
  }

?>