import { StyleSheet, View, TouchableOpacity, Text, TextInput, Platform, Pressable } from 'react-native';
import React, { useState } from 'react';
import DateTimePicker from "@react-native-community/datetimepicker";

export default function DateTimePickerComponent({ id, mode, placeholder, onDateTimePickerChange}) {
  const [dates, setDates] = useState({});
  const [showPicker, setShowPicker] = useState(false);
  // const [iosConfirm, setIosConfirm] = useState(false);
 
  const toggleDatePicker = () => {
    setShowPicker(!showPicker);
  }

  const confirmIOSDate = () => {
    const currentDate = dates[id] || new Date();
    // setIosConfirm(true);
    console.log('Confirmed Date in IOS!')
    setDates(prevDates => ({
      ...prevDates,
      [id]: currentDate,
    }));
    console.log(currentDate)
    onDateTimePickerChange(formatDate(currentDate, mode));
    toggleDatePicker();
  }

  const formatDate = (rawDate, mode) => {
    let date = new Date(rawDate);

    if (isNaN(date.getTime())) {
      return ""; // Handle NaN date, return empty string
    }

    if (mode === 'date') {
      let year = date.getFullYear();
      let month = date.getMonth() + 1;
      let day = date.getDate();

      month = month < 10 ? `0${month}` : month;
      day = day < 10 ? `0${day}` : day;

      return `${day}-${month}-${year}`;
    } else {
      let hour = date.getHours();
      let min = date.getMinutes();

      hour = hour < 10 ? `0${hour}` : hour;
      min = min < 10 ? `0${min}` : min;

      return `${hour}:${min}`;
    }
  }

  const onChange = ({ type }, selectedDate) => {
    if (type === "set") {
      const currentDate = selectedDate || new Date();
      setDates(prevDates => ({
        ...prevDates,
        [id]: currentDate,
      }));

      if (Platform.OS === "android") {
        toggleDatePicker();
      }

      if(mode == 'date'){
        if(Platform.OS == 'android'){
            // console.log("Time: ", formatDate(currentDate, mode));
            onDateTimePickerChange(formatDate(currentDate, mode));
        }
      }
      else{
        if(Platform.OS == 'android'){
            // console.log("Time: ", formatDate(currentDate, mode));
            onDateTimePickerChange(formatDate(currentDate, mode));
        }        
      }
        
    } else {
      toggleDatePicker();
    }
    
  }

  return (
    <>
      {showPicker && (
        <DateTimePicker
          mode={mode}
          display="spinner"
          value={dates[id] || new Date()}
          onChange={onChange}
          style={styles.datePicker}
        />
      )}

      {/* For IOS */}
      {showPicker && Platform.OS === "ios" && (
        <View style={{ flexDirection: "row", justifyContent: "space-around" }}>
          <TouchableOpacity style={[
            styles.btn,
            styles.pickerButton,
            { backgroundColor: "#11182711" }]} onPress={toggleDatePicker}>
            <Text style={styles.buttonText}>Cancel</Text>
          </TouchableOpacity>

          <TouchableOpacity style={[styles.btn, styles.pickerButton, { backgroundColor: '#11182711' }]} onPress={confirmIOSDate}>
            <Text style={styles.buttonText}>Confirm</Text>
          </TouchableOpacity>
        </View>
      )}

        {!showPicker && mode==='time' &&(
                <Pressable onPress={() => setShowPicker(!showPicker)}>
                    <TextInput 
                        style={styles.input}
                        placeholder={placeholder}
                        value={dates[id] ? formatDate(dates[id], mode) : ''}
                        // onChangeText={setDate1}
                        placeholderTextColor={"gray"}
                        editable={false}
                        onPressIn={toggleDatePicker}
                    />
                </Pressable>
            )}

            {!showPicker && mode==='date' &&(
                <Pressable onPress={() => setShowPicker(!showPicker)}>
                    <TextInput 
                        style={styles.input}
                        placeholder={placeholder}
                        value={dates[id] ? formatDate(dates[id].toDateString(), mode) : ''}
                        // onChangeText={setDate1}
                        placeholderTextColor={"gray"}
                        editable={false}
                        onPressIn={toggleDatePicker}
                    />
                </Pressable>
            )}
    </>
  );
}

const styles = StyleSheet.create({
  input: {
    height: 40,
    margin: 12,
    borderWidth: 1,
    borderRadius: 10,
    padding: 10,
  },
  btn: {
    height: 50,
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 50,
    marginTop: 10,
    marginBottom: 15,
    backgroundColor: '#075985'
  },
  datePicker: {
    height: 120,
    marginTop: -10,
  },
  pickerButton: {
    paddingHorizontal: 20
  },
  buttonText: {
    fontSize: 14,
    fontWeight: "500",
    color: "#f4054a"
  },
})