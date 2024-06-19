import React from 'react';
import { UserProvider } from './components/Context/UserContext'; 
import App from './App';

function AppWrapper() {
  return (
    <UserProvider>
      <App />
    </UserProvider>
  );
}

export default AppWrapper;