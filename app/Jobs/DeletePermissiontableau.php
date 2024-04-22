<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class DeletePermissiontableau implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // private $url_string;
    // private $xml_string;
    private $workbook_dashboard;
    private $xml_added_user_id;
    private $xml_site_id;
    private $xml_auth_token;

    // $workbook_dashboard, $xml_added_user_id, $xml_site_id, $xml_auth_token

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

        // $this->deleteInURL();
        // $this->putInXML("Allow");  // <-- What's the point of a variable if it's always going to be Allow?
    }
        
    // /**
    //  * deleteInURL
    //  *
    //  * @return void
    //  */
    // private function deleteInURL()
    // {
    //     $this->url_string = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
    //                         $this->xml_site_id . "/workbooks/" . 
    //                         $this->workbook_dashboard . "/permissions/users/" . 
    //                         $this->xml_added_user_id ."/ExportXml/Allow";
    // }
    
    
    /**
     * runDeleteHttpClientToApi
     *
     * @return void
     */
    private function runDeleteHttpClientToApi()
    {

        /**
         * The syntax for this request looks wrong,
         * I would test this individually first
         */
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/ExportXml/Allow", []);
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/Read/Allow", []);
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/ViewUnderlyingData/Allow", []);
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/ViewComments/Allow", []);
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/ExportImage/Allow", []);
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/AddComment/Allow", []);
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/ExportData/Allow", []);

        return 
        Http::withHeaders(
            [
                'Content-Type' => 'text/xml; charset=utf-8',
                'X-Tableau-Auth' => $this->xml_auth_token
            ]
        )
        ->send('DELETE', "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/" . 
        $this->xml_site_id . "/workbooks/" . 
        $this->workbook_dashboard . "/permissions/users/" . 
        $this->xml_added_user_id ."/Filter/Allow", []);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->runDeleteHttpClientToApi();
    }

    // private function runDeleteHttpClientToApi($xml_auth_token, $workbook_dashboard_url){
    //     $workbook_dashboard_response = Http::withHeaders(['Content-Type' => 'text/xml; charset=utf-8', 'X-Tableau-Auth' => $xml_auth_token])->send('DELETE', $workbook_dashboard_url, [
    //     ]);
    //     return $workbook_dashboard_response;
    // }
    // private function runDeleteAndHttpClient($workbook_dashboard, $xml_added_user_id, $xml_site_id, $xml_auth_token){
    //     $ExportXml_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/ExportXml/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $ExportXml_url);
    //     $Read_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/Read/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $Read_url);
    //     $ViewUnderlyingData_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/ViewUnderlyingData/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $ViewUnderlyingData_url);
    //     $ViewComments_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/ViewComments/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $ViewComments_url);
    //     $ExportImage_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/ExportImage/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $ExportImage_url);
    //     $AddComment_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/AddComment/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $AddComment_url);
    //     $ExportData_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/ExportData/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $ExportData_url);
    //     $Filter_url = "https://prod-apnortheast-a.online.tableau.com/api/3.9/sites/${xml_site_id}/workbooks/${workbook_dashboard}/permissions/users/${xml_added_user_id}/Filter/Allow";
    //     $workbook_dashboard_response = $this->runDeleteHttpClientToApi($xml_auth_token, $Filter_url);

    //     return $workbook_dashboard_response;
    // }
}
