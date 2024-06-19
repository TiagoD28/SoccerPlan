import React, { createContext, useState, useContext } from 'react';

const LoggedContext = createContext();

export const LoggedProvider = ({ children }) => {
  const [isLogged, setIsLogged] = useState();

  const value = {
    isLogged,
    setIsLogged,
  };

  return <LoggedContext.Provider value={value}>{children}</LoggedContext.Provider>;
};

export const useIsLogged = () => {
  const context = useContext(LoggedContext);
  if (!context) {
    throw new Error('useIsLogged must be used within a ThemeProvider');
  }
  return [context.isLogged, context.setIsLogged];
};