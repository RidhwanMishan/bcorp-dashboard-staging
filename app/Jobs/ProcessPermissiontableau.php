<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessPermissiontableau implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $url_string;
    private $xml_string;
    private $workbook_dashboard;
    private $xml_added_user_id;
    private $xml_site_id;
    private $xml_auth_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($workbook_dashboard, $xml_added_user_id, $xml_site_id, $xml_auth_token)
    {
        $this->workbook_dashboard = $workbook_dashboard;
        $this->xml_added_user_id = $xml_added_user_id;
        $this->xml_site_id = $xml_site_id;
        $this->xml_auth_token = $xml_auth_token;

        $this->putInURL();
        $this->putInXML("Allow");  // <-- What's the point of a variable if it's always going to be Allow?
    }
        
    /**
     * putInURL
     *
     * @return void
     */
    private function putInURL()
    {
        $this->url_string = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
                            $this->xml_site_id . "/workbooks/" . 
                            $this->workbook_dashboard . "/permissions";
    }
    
    /**
     * putInXML
     *
     * @param  string $AllowOrDeny
     * @return void
     */
    private function putInXML($AllowOrDeny)
    {
        $this->xml_string = "
        <tsRequest>
            <permissions>
                <workbook id='" . $this->workbook_dashboard . "' />
                <granteeCapabilities>
                <user id='" . $this->xml_added_user_id . "' />
                <capabilities>
                    <capability name='ExportXml' mode='" . $AllowOrDeny . "'/>
                    <capability name='Read' mode='" . $AllowOrDeny . "'/>
                    <capability name='ViewUnderlyingData' mode='" . $AllowOrDeny . "'/>
                    <capability name='ViewComments' mode='" . $AllowOrDeny . "'/>
                    <capability name='ExportImage' mode='" . $AllowOrDeny . "'/>
                    <capability name='AddComment' mode='" . $AllowOrDeny . "'/>
                    <capability name='ExportData' mode='" . $AllowOrDeny . "'/>
                    <capability name='Filter' mode='" . $AllowOrDeny . "'/>
                </capabilities>
                </granteeCapabilities>
            </permissions>
        </tsRequest>
        ";
    }
    
    /**
     * runHttpClientToApi
     *
     * @return void
     */
    private function runHttpClientToApi()
    {
        
        return Http::withHeaders(
                [
                    'Content-Type' => 'text/xml; charset=utf-8',
                    'X-Tableau-Auth' => $this->xml_auth_token
                ]
            )
            ->send('PUT', $this->url_string, ['body' => $this->xml_string]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->runHttpClientToApi();
    }
}
