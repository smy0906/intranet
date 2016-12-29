import React, { PropTypes } from 'react';
import Datetime from 'react-datetime';
import moment from 'moment';
import { Button, Form, FormGroup, FormControl, ControlLabel } from 'react-bootstrap';

class SearchBar extends React.Component {
  constructor(props) {
    super(props);

    this.options = props.options.map((option, index) => {
      return <option key={index} value={index}>{option.name}</option>;
    });
  }

  renderInput() {
    let option = this.props.options[this.props.selected];
    switch (option.dataType) {
      case 'select':
        let options = option.options.map((option, index) => {
          return <option key={index} value={option.value}>{option.name}</option>;
        });
        return (
          <FormGroup>
            <FormControl componentClass='select'
                         onChange={e => this.props.onChangeParam1(e.target.value)}>
              {options}
            </FormControl>
          </FormGroup>
        );

      case 'date':
        return  (
          <FormGroup>
            <FormGroup>
              <Datetime //value={this.props.param1}
                        inputProps={{
                          placeholder:"시작일"
                        }}
                        dateFormat='YYYY-MM-DD'
                        timeFormat={false}
                        onChange={value => {
                          if (value.format) {
                            this.props.onChangeParam1(value.format('YYYY-MM-DD'));
                          } else {
                            value = moment(value);
                            if (value.isValid()) {
                              this.props.onChangeParam1(value.format('YYYY-MM-DD'));
                            }
                          }
                        }}/>
            </FormGroup>
            <FormGroup>
              <Datetime //value={this.props.param2}
                        inputProps={{
                          placeholder:"종료일"
                        }}
                        dateFormat='YYYY-MM-DD'
                        timeFormat={false}
                        onChange={value => {
                          if (value.format) {
                            this.props.onChangeParam2(value.format('YYYY-MM-DD'));
                          } else {
                            value = moment(value);
                            if (value.isValid()) {
                              this.props.onChangeParam2(value.format('YYYY-MM-DD'));
                            }
                          }
                        }}/>
            </FormGroup>
          </FormGroup>
        );

      case 'text':
      default:
        return (
          <FormGroup>
            <FormControl type="text"
                         placeholder="Search.."
                         //value={this.props.param1}
                         onChange={e => this.props.onChangeParam1(e.target.value)}/>
          </FormGroup>
        );
    }
  }

  render() {
    return (
      <Form inline>
        <FormGroup>
          <FormControl componentClass="select"
                       value={this.props.dataField}
                       onChange={e => this.props.onChangeDataField(e)} >
            {this.options}
          </FormControl>
        </FormGroup>


        {this.renderInput()}

        {/*<Button onClick={onSearch}>검색</Button>*/}
      </Form>
    );
  }
};

SearchBar.defaultProps = {
  options: [],
  selected: 0,
  param1: undefined,
  param2: undefined,
  op: 'in',
  onSearch: undefined,
  onChangeParam: undefined,
  onChangeDataField: undefined
};

export default SearchBar;
