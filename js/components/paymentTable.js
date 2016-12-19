import React from 'react';
import update from 'react-addons-update'
import { Table, Checkbox, Button } from 'react-bootstrap';
import SearchBar from './searchBar';
import PaymentRow from './paymentRow';

class PaymentTable extends React.Component {
  constructor(props) {
    super(props);

    this.isAllSelected = false;
    this.state = {
      selects: {}
    };
  }

  selectVal(index) {
    return this.state.selects[index]? this.state.selects[index] : false;
  }

  onSelect(index) {
    this.setState({
      selects: update(this.state.selects, {[index]:{$set:!this.selectVal(index)}})
    });
  }

  onSelectAll() {
    this.isAllSelected = !this.isAllSelected;

    if (this.isAllSelected) {
      let selects = {};
      for (let i=0; i<this.props.datas.length; ++i) {
        selects[i] = true;
      }
      this.setState({selects: selects});

    } else {
      this.setState({selects: {}});

    }
  }

  onMultiEdit() {
    let selected = [];
    for (let index in this.state.selects) {
      if (this.state.selects[index]) {
        selected.push(parseInt(index));
      }
    };

    if (selected.length > 0) {
      this.props.onEdit(selected);
    }
  }

  onMultiDel() {
    let selected = [];
    for (let index in this.state.selects) {
      if (this.state.selects[index]) {
        selected.push(parseInt(index));
      }
    };

    if (selected.length > 0) {
      this.props.onDel(selected);
    }
  }

  componentWillReceiveProps(nextProps) {
    let selects = {};
    for (let i=0; i<nextProps.datas.length; ++i) {
      selects[i] = this.selectVal(i);
    }
    console.log(selects);
    this.state.selects = selects;
  }

  render() {
    let headers = Object.values(this.props.schema);
    headers = headers.map(function (headerInfo, index) {
      return <th type={headerInfo.type? headerInfo.type : 'string'}
                 key={index}>{headerInfo.name}</th>;
    });

    let rows = [];
    this.props.datas.forEach((data, index) => {
      rows.push(<PaymentRow key={index}
                            data={data}
                            selected={this.selectVal(index)}
                            onSelect={this.onSelect.bind(this, index)}
                            onEdit={this.props.onEdit.bind(undefined, [index])}
                            onDel={this.props.onDel.bind(undefined, [index])}/>);
    });

    return (
      <div>
        <SearchBar/>

        <Table>
          <thead>
            <tr>
              <th><Checkbox onChange={this.onSelectAll.bind(this)}/></th>
              {headers}
            </tr>
          </thead>
          <tbody>
              {rows}
          </tbody>
        </Table>

        <Button onClick={this.onMultiEdit.bind(this)}>편집</Button>
        <Button onClick={this.onMultiDel.bind(this)}>삭제</Button>
        <Button onClick={this.props.onAdd}>추가</Button>
      </div>
    );
  }
}

export default PaymentTable;
