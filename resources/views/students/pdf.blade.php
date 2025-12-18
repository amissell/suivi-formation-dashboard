<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des Étudiants</title>
    <style>
        @page {
            margin: 20mm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1a1a1a;
            line-height: 1.6;
            background: #ffffff;
        }
        
        /* Header Section */
        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #3b82f6;
        }
        
        .header h1 {
            color: #1e40af;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .header .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .header .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
            margin-top: 10px;
        }
        
        /* Summary Stats */
        .summary-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }
        
        .stat-box {
            flex: 1;
        }
        
        .stat-box .label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .stat-box .value {
            font-size: 20px;
            font-weight: 700;
            color: #1e40af;
        }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        thead {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
        }
        
        thead th {
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        
        tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        tbody tr:hover {
            background-color: #f1f5f9;
        }
        
        tbody td {
            padding: 10px;
            font-size: 11px;
            color: #334155;
            border: none;
        }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .badge-aide {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-vendeur {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .badge-superviseur {
            background-color: #f3e8ff;
            color: #6b21a8;
        }
        
        .badge-cdr {
            background-color: #fed7aa;
            color: #9a3412;
        }
        
        /* Attestation Badge */
        .attestation-yes {
            color: #16a34a;
            font-weight: 600;
        }
        
        .attestation-no {
            color: #dc2626;
            font-weight: 600;
        }
        
        /* Payment Styling */
        .payment-done {
            color: #16a34a;
            font-weight: 600;
        }
        
        .payment-remaining {
            color: #ea580c;
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
        
        /* Column Widths */
        .col-name { width: 15%; }
        .col-cin { width: 10%; }
        .col-phone { width: 12%; }
        .col-email { width: 15%; }
        .col-formation { width: 15%; }
        .col-date { width: 10%; }
        .col-status { width: 10%; }
        .col-attestation { width: 8%; }
        .col-payment { width: 8%; }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <h1>📚 Liste des Étudiants</h1>
        <div class="subtitle">Rapport complet des étudiants inscrits</div>
        <div class="meta-info">
            <span>Date: {{ date('d/m/Y') }}</span>
            <span>Total: {{ count($students) }} étudiant(s)</span>
        </div>
    </div>
    
    <!-- Summary Statistics -->
    <!-- <div class="summary-stats">
        <div class="stat-box">
            <div class="label">Total Étudiants</div>
            <div class="value">{{ count($students) }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Formations</div>
            <div class="value">{{ $students->unique('formation_id')->count() }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Payé</div>
            <div class="value">{{ number_format($students->sum('payment_done'), 0) }} DH</div>
        </div>
        <div class="stat-box">
            <div class="label">Total Reste</div>
            <div class="value">{{ number_format($students->sum('payment_remaining'), 0) }} DH</div>
        </div>
    </div> -->
    
    <!-- Students Table -->
    <table>
        <thead>
            <tr>
                <th class="col-name">Nom</th>
                <th class="col-cin">CIN</th>
                <th class="col-phone">Téléphone</th>
                <th class="col-email">E-mail</th>
                <th class="col-formation">Formation</th>
                <th class="col-date">Date Début</th>
                <th class="col-status">Statut</th>
                <th class="col-attestation">Attestation</th>
                <th class="col-payment">Payé</th>
                <th class="col-payment">Reste</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td><strong>{{ $student->name }}</strong></td>
                <td>{{ $student->cin }}</td>
                <td>{{ $student->phone }}</td>
                <td>{{ $student->email ?? '-' }}</td>
                <td>{{ $student->formation->name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($student->start_date)->format('d/m/Y') }}</td>
                <td>
                    @php
                        $statusClass = [
                            'aide_vendeur' => 'badge-aide',
                            'vendeur' => 'badge-vendeur',
                            'superviseur' => 'badge-superviseur',
                            'CDR' => 'badge-cdr',
                        ];
                        $statusLabels = [
                            'aide_vendeur' => 'Aide Vendeur',
                            'vendeur' => 'Vendeur',
                            'superviseur' => 'Superviseur',
                            'CDR' => 'CDR',
                        ];
                    @endphp
                    <span class="badge {{ $statusClass[$student->status] ?? 'badge-aide' }}">
                        {{ $statusLabels[$student->status] ?? ucfirst($student->status) }}
                    </span>
                </td>
                <td>
                    @if($student->attestation === 'yes')
                        <span class="attestation-yes">✓ Oui</span>
                    @else
                        <span class="attestation-no">✗ Non</span>
                    @endif
                </td>
                <td class="payment-done">{{ number_format($student->payment_done, 0) }} DH</td>
                <td class="payment-remaining">{{ number_format($student->payment_remaining, 0) }} DH</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Footer -->
    <div class="footer">
        <p>Document généré le {{ date('d/m/Y à H:i') }} | Académie de Formation</p>
        <p style="margin-top: 5px;">Ce document contient des informations confidentielles</p>
    </div>
</body>
</html>