<?php
$lang = array(
    // Page Titles
    'title1' => 'Login',
    'title2' => 'Teacher Home Page',
    'title3' => 'Generate math problems',
    'title4' => 'Grade Overview',
    'title5' => 'Student Profile',
    'title6' => 'Tutorial',
    
    // Login page
    'login' => 'Login',
    'username' => 'Username',
    'username_placeholder' => 'Enter username',
    'password_placeholder' => 'Enter password',
    'password' => 'Password',
    'submit' => 'Login',
    'register-text' => 'Register',

     // Teacher home page
    'header' => 'Teacher Home Page',
    'menu1' => 'Assignment Submission',
    'menu2' => 'Student Grade Overview',
    'menu3' => 'User Manual',
    'welcome' => 'Welcome to the home page',
    'rights' => '© 2023 - Teacher Home Page.',

    // Generator
    'file-input' => 'Select LaTeX file:',
    'problem-count' => 'Enter number of problems:',
    'generator' => 'Generate problems from LaTeX file',
    'problem' => 'Problem',
    'solution' => 'Solution',
    'select' => 'Select LaTeX file:',
    'generate' => 'Generate',
    'sets' => 'Assignment sets',
    'asgName' => 'Assignment name',
    'asgDateStart' => 'Date from',
    'asgDateEnd' => 'Date to',
    'asgPoints' => 'Points',
    'deleteBtn' => 'Delete',
    'asgNoSetsError' => 'No assignment sets found.',
    'asgInsertError' => 'Assignment set with the same details already exists.',
    'asgInsertSuccess' => 'Assignment set successfully created.',
    'dbsFileTitle' => 'Files in the Database',
    'fileName' => 'File name',
    'fileUploadDate' => 'Upload date',
    'fileActions' => 'Actions',
    'showBtn' => 'Show',
    'noFilesError' => 'No files found in the database.',
    'connFail' => 'Connection to the database failed ',
    'createAsg' => 'Create assignment',
    'selectSet' => 'Select set',
    'create' => 'Create',
    'texOnly' => 'Only .tex files are allowed',
    'uploadMsg' => 'Please upload a file',

    // Overview
    'firstName' => 'First name',
    'lastName' => 'Last name',
    'studentID' => 'Student ID',
    'setName' => 'Assignment name',
    'tests' => 'Generated problems',
    'points' => 'Points',
    'exportCSV' => 'Export to CSV',
    'exportPDF' => 'Export to PDF',
    'goBack' => 'Back to Grade Overview',

    // Student profile
    'tutorial' => 'Tutorial',
    'tSite' => 'Teacher\'s site',
    'teacher-tuto' => 'The teacher\'s homepage is divided into three subpages. The first subpage, titled "Assignment Submission", allows the teacher to upload a LaTeX file with examples structured as attached LaTeX files, blokovkaXXpr.tex or odozvaXXpr.tex. After uploading these files, the example data is saved in the database, and a preview of each example from the file is displayed to the teacher to check the correctness of the uploaded data. After uploading the file, it is possible to create tests for students. This is done by selecting a file of examples from the database from which the specific set should be created. Then, the teacher enters the test name, specifies the availability period (from when to when the test should be accessible), and the number of points that students can earn for this test. Finally, by clicking the "Create" button, the test is created and saved in the database. Information about all files in the database and created tests is displayed in the <strong>Database Files</strong> and the <strong>Assignment tests</strong>, along with the option to delete this data from the database. <strong>After uploading, it is necessary to refresh the page to update the tables</strong>. The next subpage, titled Student Grade Overview, contains a table of all students that can be sorted by name, surname, ID, and the number of points from tests. It is possible to click on any student to display their personal profile, where information about all the tests they have taken and the points they have earned are displayed. Below the table, there is a button that allows exporting this data to a CSV file.',
    
);
