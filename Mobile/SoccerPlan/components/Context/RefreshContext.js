import React, { createContext, useState, useContext } from 'react';

const RefreshContext = createContext();

export const RefreshProvider = ({ children }) => {
  const [isRefreshPressed, setIsRefreshPressed] = useState(false);

  const setRefreshPressed = (value) => {
    setIsRefreshPressed(value);
  };

  return (
    <RefreshContext.Provider value={{ isRefreshPressed, setRefreshPressed }}>
      {children}
    </RefreshContext.Provider>
  );
};

export const useRefresh = () => {
  const context = useContext(RefreshContext);
  if (!context) {
    throw new Error('useRefresh must be used within a RefreshProvider');
  }
  return context;
};