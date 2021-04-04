<?php
/**
 * Database Connection Class
 *
 * @author Anna
 *
 */
class Database {
    private 
        $_connect   = null,
        $_env       = null,
        $_error     = '';

    //constructor
    public function __construct($env = 'dev') {
        $this->_env = $env;
        switch ($this->_env) {
            case 'dev':
                $this->_connect = mysqli_connect("localhost", "root", "", "livingde_zgame") or die("Database connection failed.");
                break;
            case 'staging':
                $this->_connect = mysqli_connect("localhost", "id6507481_root", "1q2w3e4r5t", "id6507481_livingde_zgame") or die("Database connection failed.");
                break;
            case 'production':
                $this->_connect = mysqli_connect("localhost", "livingde_anna", "reports18", "livingde_zgame") or die("Database connection failed.");
                break;
            default:
                $this->_connect = mysqli_connect("localhost", "livingde_anna", "reports18", "livingde_zgame") or die("Database connection failed.");
        }

        if (mysqli_connect_errno()) {
            $this->_connect = null;
            $this->_error = "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    }

    public function getConnect() {
        return $this->_connect;
    }
    
    public function getEnv() {
        return $this->_env;
    }

    public function getError() {
        return $this->_error;
    }
}