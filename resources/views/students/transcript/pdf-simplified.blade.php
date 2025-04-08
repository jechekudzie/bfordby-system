<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transcript - {{ $student->first_name }} {{ $student->last_name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
            line-height: 1.5;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #154832;
            padding-bottom: 10px;
        }
        
        .header h1 {
            color: #154832;
            margin: 0;
            font-size: 22px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .student-info {
            margin-bottom: 20px;
            width: 100%;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }
        
        .student-info th, .student-info td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        .student-info th {
            background-color: #f2f2f2;
            font-weight: bold;
            width: 30%;
        }
        
        .semester {
            margin-bottom: 20px;
        }
        
        .semester-header {
            background-color: #154832;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .subject {
            margin-bottom: 15px;
        }
        
        .subject-header {
            background-color: #FBD801;
            color: #154832;
            padding: 6px;
            font-weight: bold;
            font-size: 13px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }
        
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .centered {
            text-align: center;
        }
        
        .grade {
            font-weight: bold;
        }
        
        .classification {
            padding: 3px 5px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .distinction, .merit, .credit, .pass {
            background-color: #154832;
            color: white;
        }
        
        .fail {
            background-color: #dc3545;
            color: white;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #777;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .grade-info {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
        }
        
        .grade-info h3 {
            margin-top: 0;
            font-size: 14px;
            color: #154832;
        }
        
        .grade-info table {
            margin-bottom: 0;
        }
        
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        
        .badge-success {
            background-color: #FBD801;
            color: #154832;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            @php
                $logoPath = public_path('logo.png');
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoSrc = 'data:image/png;base64,' . $logoData;
            @endphp
            <img src="{{ $logoSrc }}" alt="Blackfordby College Logo" style="max-height: 80px; max-width: 200px; display: block; margin: 0 auto 10px;">
        </div>
        <h1>ACADEMIC TRANSCRIPT</h1>
        <p>Blackfordby College of Agriculture</p>
        <p>Date: {{ $date }}</p>
    </div>
    
    <table class="student-info">
        <tr>
            <th>Student Name</th>
            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
            <th>Student ID</th>
            <td>{{ $student->student_number }}</td>
        </tr>
        <tr>
            <th>Program</th>
            <td>{{ $student->enrollments->first()->course->name ?? 'N/A' }}</td>
            <th>Status</th>
            <td>{{ $student->enrollments->first()->status ?? 'N/A' }}</td>
        </tr>
    </table>
    
    @foreach($transcriptData as $trimester)
        <div class="semester">
            <div class="semester-header">
                Year {{ $trimester['semester']->academic_year }} - {{ $trimester['semester']->name }}
            </div>
            
            @foreach($trimester['subjects'] as $subject)
                <div class="subject">
                    <div class="subject-header">
                        {{ $subject['subject']->name }}
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th width="60%">Module</th>
                                <th width="20%" class="centered">Status</th>
                                <th width="20%" class="centered">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject['modules'] as $module)
                                <tr>
                                    <td>
                                        <strong>{{ $module['module']->name }}</strong>
                                    </td>
                                    <td class="centered">
                                        @php
                                            $isComplete = $module['grade'] !== null;
                                        @endphp
                                        
                                        @if($isComplete)
                                            Completed
                                        @else
                                            Incomplete
                                        @endif
                                    </td>
                                    <td class="centered">
                                        @if($isComplete)
                                            <span class="classification {{ strtolower($module['grade_classification']) }}">
                                                {{ $module['grade_classification'] }}
                                            </span>
                                        @else
                                            --
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
        
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
    
    <div class="grade-info">
        <h3>Grade Classification</h3>
        <table>
            <tr>
                <th>Classification</th>
                <th>Grade Range</th>
                <th>Description</th>
            </tr>
            <tr>
                <td>Distinction</td>
                <td>75% and above</td>
                <td>Exceptional achievement demonstrating outstanding knowledge</td>
            </tr>
            <tr>
                <td>Merit</td>
                <td>65% - 74%</td>
                <td>Very good achievement with thorough understanding</td>
            </tr>
            <tr>
                <td>Credit</td>
                <td>60% - 64%</td>
                <td>Good achievement with sound understanding</td>
            </tr>
            <tr>
                <td>Pass</td>
                <td>50% - 59%</td>
                <td>Satisfactory achievement meeting minimum requirements</td>
            </tr>
            <tr>
                <td>Fail</td>
                <td>Below 50%</td>
                <td>Insufficient achievement, remedial work required</td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>This transcript is unofficial unless signed by an authorized officer of the institution.</p>
        <p>Bfordby Agricultural Institute &copy; {{ date('Y') }}</p>
    </div>
</body>
</html> 