<?php
class aDateTime extends DateTime {
    public function getTimestamp() {
         return method_exists('DateTime', 'getTimestamp') ? parent::getTimestamp() : $this->format('U');
    }
}