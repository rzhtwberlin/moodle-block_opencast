@block @block_opencast
Feature: Report problems
  In order to fix video issues
  As admins
  Teachers need to be able to report problems to the support team

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                | idnumber |
      | teacher1 | Teacher   | 1        | teacher1@example.com | T1       |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following config values are set as admin:
      | config              | value                                                         | plugin         |
      | apiurl              | http://testapi:8080                                           | tool_opencast  |
      | apipassword         | opencast                                                      | tool_opencast  |
      | apiusername         | admin                                                         | tool_opencast  |
      | ocinstances         | [{"id":1,"name":"Default","isvisible":true,"isdefault":true}] | tool_opencast  |
      | limituploadjobs_1   | 0                                                             | block_opencast |
      | group_creation_1    | 0                                                             | block_opencast |
      | group_name_1        | Moodle_course_[COURSEID]                                      | block_opencast |
      | series_name_1       | Course_Series_[COURSEID]                                      | block_opencast |
      | enablechunkupload_1 | 0                                                             | block_opencast |
      | support_email_1     | test@test.de                                                  | block_opencast |
    And I setup the opencast test api
    And I upload a testvideo
    And I log in as "admin"
    And I am on "Course 1" course homepage with editing mode on
    And I add the "Opencast Videos" block

  @javascript
  Scenario: When the update metadata form is loaded, the video metadata are loaded in the form
    When I click on "Go to overview..." "link"
    And I click on "#opencast-videos-table-1234-1234-1234-1234-1234_r0 .c6 a.report-problem" "css_element"
    And I set the field "inputMessage" to "This is a message."
    And I click on "Report problem" "button"
    Then I should see "The email was successfully sent to the support team"


