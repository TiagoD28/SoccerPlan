import React, { useState } from 'react';
import { View, TextInput, Text, StyleSheet, TouchableOpacity } from 'react-native';
import { RadioButtons, SegmentedControls } from 'react-native-radio-buttons';

const MyTextInput = ({navigation}) => {
  const [number, setNumber] = useState('');
  const [cod, setCod] = useState('');
  const [selectedType, setSelectedType] = useState(null);

  const onChangeNumber = (text) => {
    setNumber(text);
  };    

  const renderSegmentControlClone = () => {
    const options = [
      'Admin',
      'Coach',
      'Player',
    ];

    function setSelectedOption(selectedSegment) {
      // Handle the selected segment as needed
      console.log('Selected option:', selectedSegment);
      setSelectedType(selectedSegment) 
    }

    return (
      <View style={{ marginTop: 10, padding: 20, backgroundColor: 'white' }}>
        <Text style={{ paddingBottom: 10, fontWeight: 'bold' }}>SegmentedControl</Text>
        {/* Assuming SegmentedControls is a component you have defined */}
        <SegmentedControls
          options={options}
          // allowFontScaling={ false }
          onSelection={setSelectedOption}
          selectedOption={selectedType} // Assuming you want to use the same state for selectedType
          backgroundColor='#041b2b'
          tint={'white'}
          selectedTint={'white'}
          selectedBackgroundColor={'#f50443'}
          separatorTint={'white'}
          optionStyle={{
            fontSize: 17
          }}
          optionContainerStyle={{height: 40, justifyContent: 'center', alignItems: 'center'}}
        />
        <Text style={{ marginTop: 10 }}>Selected option: {selectedType || 'none'}</Text>
      </View>
    );
  };

  return (
    <View style={{marginTop: 20}}>
        {renderSegmentControlClone()}
        {/* <Text>Selected Type: {selectedType}</Text> */}
      <View style={styles.inputContainer}>  
        <Text style={styles.placeholderText}>{}</Text>
        <TextInput
          style={styles.input}
          onChangeText={onChangeNumber}
          value={number}
          placeholder="placeholder"
          placeholderTextColor="black"
        />
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    borderColor: 'gray',
    borderWidth: 1,
    padding: 10,
  },
  placeholderText: {
    color: 'red',
  },
  input: {
    flex: 1,
    height: 40,
  },
});

export default MyTextInput;