<?php


class responsehelper {
	public function makeResponseWithErr($detail) {
		return json_encode(
			array(
				'status'=> -1,
				'description'=> $detail
			)
		);
	}

	public function makeResponse($detail, $data) {
		return json_encode(
			array(
				'data'=> $data,
				'status'=> 0,
				'description'=> $detail
			)
		);
	}
}

?>