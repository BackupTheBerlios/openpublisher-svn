<?php
interface JapaInterfaceRequest {
    public function issetParameter($name, $context = 'request' );
    public function getParameter($name, $context = 'request' );
    public function getParameterNames( $context = 'request' );
    public function getHeader($name);
}
?>