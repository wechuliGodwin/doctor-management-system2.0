<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8fafc;
        min-height: 100vh;
        color: #1e293b;
    }

    .dashboard-container {
        padding: 24px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .header {
        text-align: center;
        margin-bottom: 24px;
        padding: 16px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .header h1 {
        color: #159ed5;
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .cards-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }

    .full-width {
        grid-template-columns: 1fr;
    }

    .card {
        background: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-container {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .search-input {
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
        background: #fff;
        transition: border-color 0.2s ease;
        flex: 1;
    }

    .search-input:focus {
        outline: none;
        border-color: #159ed5;
        box-shadow: 0 0 0 2px rgba(21, 158, 213, 0.1);
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary {
        background: #159ed5;
        color: white;
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
        padding: 6px 10px;
        font-size: 0.8rem;
    }

    .btn:hover {
        filter: brightness(110%);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .search-results {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        max-height: 200px;
        overflow-y: auto;
        margin-bottom: 16px;
        display: none;
    }

    .search-result-item {
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
    }

    .search-result-item:hover {
        background: #f1f5f9;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .selected-patients {
        max-height: 400px;
        overflow-y: auto;
    }

    .patient-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .patient-item:hover {
        background: #f1f5f9;
    }

    .patient-item:last-child {
        border-bottom: none;
    }

    .patient-info {
        display: flex;
        gap: 10px;
        align-items: center;
        flex: 1;
    }

    .patient-name,
    .patient-specialization,
    .patient-date {
        font-size: 0.9rem;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .patient-name {
        font-weight: 600;
        color: #1e293b;
    }

    .patient-specialization {
        color: #64748b;
    }

    .patient-date {
        color: #159ed5;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        margin-right: 8px;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-sent {
        background: #d1fae5;
        color: #065f46;
    }

    .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .template-options {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .template-option {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.9rem;
        background: #fff;
        transition: all 0.2s ease;
    }

    .template-option:hover {
        border-color: #159ed5;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .template-option.selected {
        border-color: #159ed5;
        background: #e6f4fa;
        color: #159ed5;
    }

    .message-input {
        width: 100%;
        min-height: 100px;
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.9rem;
        resize: vertical;
        transition: border-color 0.2s ease;
    }

    .message-input:focus {
        outline: none;
        border-color: #159ed5;
        box-shadow: 0 0 0 2px rgba(21, 158, 213, 0.1);
    }

    .message-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
        font-size: 0.9rem;
        color: #64748b;
    }

    .variable-chips {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .variable-chip {
        background: #159ed5;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .variable-chip:hover {
        filter: brightness(110%);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .preview-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 16px;
    }

    .preview-message {
        background: white;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        max-width: 300px;
        font-size: 0.9rem;
    }

    .delivery-log {
        max-height: 400px;
        overflow-y: auto;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 10px;
    }

    .delivery-log-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
        table-layout: fixed;
    }

    .delivery-log-table th,
    .delivery-log-table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .delivery-log-table th {
        background: #f1f5f9;
        font-weight: 600;
        color: #1e293b;
        width: 8.33%;
    }

    .delivery-log-table tr:hover {
        background: #f1f5f9;
    }

    .delivery-log-table .status-sent {
        color: #065f46;
        background: #d1fae5;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .delivery-log-table .status-failed {
        color: #991b1b;
        background: #fee2e2;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .delivery-log-table .status-delivered,
    .delivery-log-table .status-queued,
    .delivery-log-table .status-noreport {
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .delivery-log-table .status-delivered {
        background: #d1fae5;
        color: #065f46;
    }

    .delivery-log-table .status-queued {
        background: #fef3c7;
        color: #92400e;
    }

    .delivery-log-table .status-noreport {
        background: #e2e8f0;
        color: #1e293b;
    }

    .send-section {
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
    }

    .send-actions {
        display: flex;
        gap: 8px;
    }

    .progress-bar {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin: 8px 0;
        display: none;
    }

    .progress-fill {
        height: 100%;
        background: #159ed5;
        width: 0%;
        transition: width 0.3s ease;
    }

    .sending-status {
        font-size: 0.9rem;
        color: #64748b;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.9rem;
        margin-top: 8px;
        display: none;
    }
</style>