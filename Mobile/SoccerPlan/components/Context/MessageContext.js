import React, { createContext, useState, useContext } from 'react';

const MessageContext = createContext();

export const MessageProvider = ({ children }) => {
  // const [isRefreshPressed, setIsRefreshPressed] = useState(false);
  const [isMessageSended, setIsMessageSended] = useState(false);

  const setMessageSended = (value) => {
    setIsMessageSended(value);
  };

  return (
    <MessageContext.Provider value={{ isMessageSended, setMessageSended }}>
      {children}
    </MessageContext.Provider>
  );
};

export const useMessage = () => {
  const context = useContext(RefreshContext);
  if (!context) {
    throw new Error('useMessage must be used within a RefreshProvider');
  }
  return context;
};