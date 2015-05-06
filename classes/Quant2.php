<?php

namespace MySociety\Logbook;

use Illuminate\Database\Capsule\Manager as Capsule;

class Quant2 {

    private function recordEvent($site, $page, $bucket, $event, $data = null, $timer = null) {

        Capsule::table('quant2-log')->insert([
            'timestamp' => time(),
            'site' => $site,
            'page' => $page,
            'bucket' => (int) $bucket,
            'event' => $event,
            'data' => $data,
            'timer' => (int) $timer
        ]);
    }

    public function handleEvent($request, $response) {

        // We must always have a site, page, bucket and event.
        if ($request->query->get('site') == null
            || $request->query->get('page') == null
            || $request->query->get('bucket') == null
            || $request->query->get('method') == null
        ) {

            $response_code = 400;
            $response_content = array(
                'success' => false,
                'message' => 'You must include a site, page, bucket and method.'
            );

        } else {

            switch ($request->query->get('method')) {

                // Valid for all buckets
                case 'view':

                    $this->recordEvent(
                        $request->query->get('site'),
                        $request->query->get('page'),
                        $request->query->get('bucket'),
                        'view'
                    );
                    break;

                // Only ever in Bucket 1
                case 'show_popup':

                    $this->recordEvent(
                        $request->query->get('site'),
                        $request->query->get('page'),
                        1,
                        'show_popup',
                        $request->query->get('data'),
                        (int) $request->query->get('timer')
                    );

                    break;

                // Only ever in Bucket 1
                case 'suppressed_popup':

                    $this->recordEvent(
                        $request->query->get('site'),
                        $request->query->get('page'),
                        1,
                        'suppressed_popup',
                        $request->query->get('data'),
                        (int) $request->query->get('timer')
                    );

                    break;

                // Only ever in Bucket 1
                case 'click_popup_link':

                    $this->recordEvent(
                        $request->query->get('site'),
                        $request->query->get('page'),
                        1,
                        'click_popup_link',
                        $request->query->get('data'),
                        (int) $request->query->get('timer')
                    );

                    break;

                // Only ever in Bucket 2
                case 'click_nav_link':

                    $this->recordEvent(
                        $request->query->get('site'),
                        $request->query->get('page'),
                        2,
                        'click_nav_link',
                        $request->query->get('data'),
                        (int) $request->query->get('timer')
                    );

                    break;

                default:

                    // No idea what's being attempted.
                    $response_code = 400;
                    $response_content = array(
                        'success' => false,
                        'message' => '"' . htmlspecialchars($request->query->get('method')) . '" is not a valid method.'
                    );

                    // Get us out of here, we know this has gone wrong.
                    return;

            }

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

        return $response;
    }

}
