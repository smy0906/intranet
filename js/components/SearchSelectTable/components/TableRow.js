import React from 'react';
import {Button, Checkbox} from 'react-bootstrap';

class TableRow extends React.Component {
  render() {
    const cols = Object.values(this.props.data).map((data, index) => {
      return <td key={index}>{data}</td>;
    });

    return (
      <tr>
        <td><Checkbox checked={this.props.selected}
                      onChange={this.props.onSelect}/></td>
        {cols}
        <td>
          <Button onClick={this.props.onEdit}>편집</Button>
          <Button onClick={this.props.onDel}>삭제</Button>
        </td>
      </tr>
    );
  }
}

export default TableRow;
