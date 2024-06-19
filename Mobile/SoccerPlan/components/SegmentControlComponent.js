import React, {useState, useEffect} from 'react';
import { StyleSheet, View, Text, Image, useWindowDimensions, TextInput, TouchableOpacity} from 'react-native';
import { useNavigation } from '@react-navigation/native';
import RadioButtons, { SegmentedControls } from 'react-native-radio-buttons';
// import useData from '../getPostApi/useData';
import Config from '../getPostApi/config';

export default function SegmentedControlComponent( {onTypeSelected, options} ){
  // const [selectedType, setSelectedType] = useState(null);
  const [selectedType, setSelectedType] = useState('')
    function setSelectedOption(selectedSegment) {
      // Handle the selected segment as needed
      console.log('Selected option:', selectedSegment);
      setSelectedType(selectedSegment),
      onTypeSelected(selectedSegment);
    }

    return (
      <View style={{alignItems: 'center'}}>
      <View style={{ width: '100%', marginTop: 10, padding: 10, backgroundColor: 'white' }}>
        {/* <Text style={{ paddingBottom: 10, fontWeight: 'bold', textTransform: 'uppercase'}}>User Type: {selectedType || 'none'}</Text> */}
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
      </View>
      </View>
    );
}