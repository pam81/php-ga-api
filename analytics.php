<?php
// Load the Google API PHP Client Library.
require_once __DIR__ . '/vendor/autoload.php';

class Analytics {
    private $analytics;
    /**
     * Initializes an Analytics Reporting API V4 service object.
     *
     * @return An authorized Analytics Reporting API V4 service object.
     */
    public function __construct($KEY_FILE_LOCATION)
    {
      // Create and configure a new client object.
      $client = new Google_Client();
      $client->setApplicationName("Analytics Reporting");
      $client->setAuthConfig($KEY_FILE_LOCATION);
      $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
      $this->analytics = new Google_Service_AnalyticsReporting($client);
    }

    /**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */
    public function getReport($VIEW_ID) {

      
      // Create the DateRange object.
      $dateRange = new Google_Service_AnalyticsReporting_DateRange();
      $dateRange->setStartDate("7daysAgo");
      $dateRange->setEndDate("today");

      // Create the Metrics object.
      $sessions = new Google_Service_AnalyticsReporting_Metric();
      $sessions->setExpression("ga:sessions");
      $sessions->setAlias("sessions");
      
      //Add Event
      $totalEvents = new Google_Service_AnalyticsReporting_Metric();
      $totalEvents->setExpression("ga:totalEvents");
      $totalEvents->setAlias("totalEvents");

      // Create Dimension Filter.
      $dimensionCategoryFilter = new Google_Service_AnalyticsReporting_Dimension();
      $dimensionCategoryFilter->setName("ga:eventCategory");

      $dimensionActionFilter = new Google_Service_AnalyticsReporting_Dimension();
      $dimensionActionFilter->setName("ga:eventAction");
      //$dimensionFilter->setOperator("EXACT");
      //$dimensionFilter->setExpressions(array("search")); //name of category

      // Create the ReportRequest object.
      $request = new Google_Service_AnalyticsReporting_ReportRequest();
      $request->setViewId($VIEW_ID);
      $request->setDateRanges($dateRange);
      $request->setDimensions(array($dimensionCategoryFilter,$dimensionActionFilter));
      $request->setMetrics(array($sessions, $totalEvents));

      $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
      $body->setReportRequests( array( $request) );
      return $this->analytics->reports->batchGet( $body );
    }

    public function printResults($reports) {
      for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
        $report = $reports[ $reportIndex ];
        $header = $report->getColumnHeader();
        $dimensionHeaders = $header->getDimensions();
        $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
        $rows = $report->getData()->getRows();
    
        for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
          $row = $rows[ $rowIndex ];
          $dimensions = $row->getDimensions();
          $metrics = $row->getMetrics();
          for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
            print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
          }
    
          for ($j = 0; $j < count($metrics); $j++) {
            $values = $metrics[$j]->getValues();
            for ($k = 0; $k < count($values); $k++) {
              $entry = $metricHeaders[$k];
              print($entry->getName() . ": " . $values[$k] . "\n");
            }
          }
        }
      }
    }


}