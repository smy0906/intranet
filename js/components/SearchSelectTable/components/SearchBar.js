import React, { PropTypes } from 'react';
import { Button, Form, FormGroup, FormControl } from 'react-bootstrap';

const SearchBar = ({options, onSearch}) => {
  options = options.map((option, index) => {
    return <option key={index} value={option.value}>{option.name}</option>;
  });

  return (
    <Form inline>
      <FormGroup>
        <FormControl componentClass="select">
          {options}
        </FormControl>
      </FormGroup>

      <FormGroup controlId="formControlsText">
        <FormControl type="text" placeholder="Search.."/>
      </FormGroup>

      <Button onClick={onSearch}>검색</Button>
    </Form>
  );
};

SearchBar.defaultProps = {
  options: [],
  onSearch: undefined,
};

export default SearchBar;
