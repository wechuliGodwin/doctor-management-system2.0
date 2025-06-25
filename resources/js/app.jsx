import './bootstrap.js';
import React from 'react';
import ReactDOM from 'react-dom/client';
import AlertToast from './components/AlertToast.jsx';

//popup alert
// Find the root element where the React component will be mounted
const alertRootElement = document.getElementById('react-alert-root');

if (alertRootElement) {
  // Get the data attributes passed from Blade
  const alertsCount = parseInt(alertRootElement.dataset.alertsCount || '0', 10);
  const alertsRoute = alertRootElement.dataset.alertsRoute || '/booking/alerts';

  // Create a React root and render the AlertToast component
  ReactDOM.createRoot(alertRootElement).render(
    <React.StrictMode>
      <AlertToast alertsCount={alertsCount} alertsRoute={alertsRoute} />
    </React.StrictMode>
  );
}