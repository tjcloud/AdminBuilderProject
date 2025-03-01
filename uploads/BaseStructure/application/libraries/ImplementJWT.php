<?php
	require APPPATH.'/libraries/JWT.php';
	
	class ImplementJwt
	{
		PRIVATE $key = "hair_saloon";
		
		public function GenerateToken($data)
		{
			$jwt = JWT::encode($data,$this->key);
			return $jwt;
		}
		
		public function DecodeToken($token)
		{
			$decoded = JWT::decode($token,$this->key,array('HS256'));
			$decodeData = (array) $decoded;
			return $decodeData;
		}
	}
?>