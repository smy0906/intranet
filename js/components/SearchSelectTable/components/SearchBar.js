import React, { PropTypes } from 'react';
import { Button, Form, FormGroup, FormControl } from 'react-bootstrap';

const SearchBar = ({options, dataField, op, param, onChangeDataField, onChangeParam, onSearch}) => {
  options = options.map((option, index) => {
    return <option key={index} value={option.dataField}>{option.name}</option>;
  });

  return (
    <Form inline>
      <FormGroup>
        <FormControl componentClass="select"
                     value={dataField}
                     onChange={e => onChangeDataField(e)} >
          {options}
        </FormControl>
      </FormGroup>

      <FormGroup controlId="formControlsText">
        <FormControl type="text"
                     placeholder="Search.."
                     value={param}
                     onChange={e => onChangeParam(e)} />
      </FormGroup>

      {/*<Button onClick={onSearch}>검색</Button>*/}
    </Form>
  );
};

SearchBar.defaultProps = {
  options: [],
  dataField: undefined,
  op: 'in',
  param: '',
  onSearch: undefined,
};

export default SearchBar;
