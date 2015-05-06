<?php

namespace MySociety\Logbook;

class Home {

    public function showHome($request, $response) {
        $response->setContent(json_encode([
            'success' => true,
            'message' => 'Logbook.'
        ]));
        $response->headers->add(['Content-Type' => 'application/json']);
        return $response;
    }

}
