<?php

namespace MySociety\Logbook;

use Illuminate\Database\Capsule\Manager as Capsule;

class MzalendoFacebook {

    private function recordEvent($sid, $action, $value = null) {

        Capsule::table('mzalendo_fb_log')->insert([
            'timestamp' => time(),
            'sid' => $sid,
            'action' => $action,
            'value' => $value,
        ]);
    }

    public function handleEvent($request, $response) {

        // We must always have a sid and action.
        if ($request->query->get('sid') == null
            || $request->query->get('action') == null
        ) {

            $response_code = 400;
            $response_content = array(
                'success' => false,
                'message' => 'You must include a sid and action.'
            );

        } else {

            // Record it!
            $this->recordEvent(
                $request->query->get('sid'),
                $request->query->get('action'),
                $request->query->get('value')
            );

            // Method is valid, we've done a thing, send a reply.

            $response_content = array(
                'success' => true
            );
        }

        if (isset($response_code)) {
            $response->setStatusCode($response_code);
        }

        // Are we doing JSONP here? We probably should be, but check anyway.
        if ($request->query->get('callback') !== null) {

            $response->setContent($request->query->get('callback') . '(' . json_encode($response_content) . ')');
            $response->headers->add(['Content-Type' => 'application/javascript']);

        } else {

            $response->setContent(json_encode($response_content));
            $response->headers->add(['Content-Type' => 'application/json']);

        }

        // CORS stops browsers from complaining, even though the request has worked
        $response->headers->add(['Access-Control-Allow-Origin' => '*']);

        return $response;
    }

}
