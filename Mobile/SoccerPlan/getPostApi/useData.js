import { useState, useEffect} from "react";
import axios from "axios";

export default function useData(apiUrl) {
  const [data, setData] = useState({ data: [] });
  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(apiUrl);
        const responseData = response.data;
        // console.log(responseData)
        setData(responseData);
      } catch (error) {
        console.error("API error: ", error);
      }
    };

    // Fetch data initially
    fetchData();

    // Fetch data every 10 seconds
    // const intervalId = setInterval(fetchData, 10000);

    // Cleanup interval on component unmount
    // return () => clearInterval(intervalId);
  }, [apiUrl]);
  
  return data;
}